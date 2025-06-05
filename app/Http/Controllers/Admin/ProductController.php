<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
     {
     $products = Product::latest()->get();
     return view('products.index', compact('products'));
     }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // Mengambil semua produk dari database
        $category = category::all();
return view('products.create',compact('category'));

    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
// Membuat validator manual
$validator = Validator::make($request->all(), [
'name' => 'required|string|max:255',
'description' => 'nullable|string',
'price' => 'required|integer',
'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
'address' => 'required|string',
'category' => 'required|string',
]);

// Jika validasi gagal
if ($validator->fails()) {
return redirect()->back()
->withErrors($validator)
->withInput();
}

$extension = $request->file('image')->getClientOriginalExtension();

// Buat nama file unik menggunakan timestamp dan slug
$filename = $request->name . time() . '.' . $extension;

// Simpan file dengan nama baru ke folder 'images' pada disk 'public'
$imagePath = $request->file('image')->storeAs('images', $filename, 'public');


// Menyimpan produk ke database
Product::create([
'name' => $request->name,
'description' => $request->description,
'price' => $request->price,
'is_available' => $request->has('is_available'),
'image' => $imagePath,
'address' => $request->address,
'category' => $request->category,
]);

// Redirect ke halaman produk dengan pesan sukses
return redirect("/product");
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //





    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
           return view('products.edit', [
           'product' => Product::findOrFail($id)
           ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
     // Mencari produk berdasarkan ID
     $product = Product::findOrFail($id);

     // Menggunakan Validator untuk memvalidasi input dari user
     $validator = Validator::make($request->all(), [
     'name' => 'required|string|max:255',
     'description' => 'nullable|string',
     'price' => 'required|integer|min:0',
     'is_available' => 'required|boolean',
     'category' => 'nullable|string|max:255',
     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
     ]);

     // Jika validasi gagal, kembalikan ke form dengan error
     if ($validator->fails()) {
     return redirect()->back()
     ->withErrors($validator)
     ->withInput();
     }

     // Jika validasi berhasil, lanjutkan untuk update data

     // Jika ada gambar baru yang di-upload
     if ($request->hasFile('image')) {
     // Hapus gambar lama jika ada
     if ($product->image) {
     Storage::delete( 'public/'.$product->image);
    //  dd($product->image);
     }


$extension = $request->file('image')->getClientOriginalExtension();

// Buat nama file unik menggunakan timestamp dan slug
$filename = $request->name . time() . '.' . $extension;

// Simpan file dengan nama baru ke folder 'images' pada disk 'public'
$imagePath = $request->file('image')->storeAs('images', $filename, 'public');


     // Simpan gambar baru
    //  $imagePath = $request->file('image')->store('images', 'public');
     $validatedData['image'] = $imagePath;
    //  $validatedData['image'] = $imagePath;

     }

     // Update produk dengan data yang sudah tervalidasi
     $product->update($validatedData);

     // Redirect ke halaman daftar produk dengan pesan sukses
return redirect('/product')
     ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $product = Product::findOrFail($id);
        // Hapus gambar dari storage
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }
        // Hapus produk dari database
        $product->delete();
        // Redirect ke halaman daftar produk dengan pesan sukses
        return redirect('/product')
            ->with('success', 'Product deleted successfully!');
    }
}
