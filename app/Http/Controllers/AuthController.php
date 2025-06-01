<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\DataMahasiswa;
use App\Mail\resendlink;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{

      // Registrasi
      public function register(Request $request)
      {
      $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'nim' => 'required|string|unique:data_mahasiswa,nim',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:6|confirmed',
      'ktmm' => 'required|image|mimes:jpeg,png,jpg|max:2048',
      'nama_mabna' => 'required|string|max:255',

      'no_kamar' => 'required|string|max:10',
      'link_ktmm' => 'nullable|url|max:2048',
      'no_hp' => 'required|string|max:15',
      ]);

      if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
      }
      if ($request->hasFile('ktmm')) {
      $file = $request->file('ktmm');

      // Ambil ekstensi file
      $extension = $file->getClientOriginalExtension();

      // Rename file sesuai NIM dan nama
      $filename = $request->nim . "_" . $request->name . '.' . $extension;

      // Simpan ke folder public/ktmm
      $path = $file->storeAs('images', $filename, 'public');

      // Simpan path untuk ke DB
      }
      else {
      return response()->json(['message' => 'bukan file'], 422);

      }

      DB::beginTransaction();

      try {
      // Simpan ke tabel users
      $user = User::create([
      'name' => $request->name,
      // 'nim' => $request->nim,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      // 'ktmm' => $path, // Simpan path ke DB
      ]);

      $token = $user->createToken('auth_token')->plainTextToken;

      $user->update(['token' => $token]);


      // Simpan ke tabel data_mahasiswa
      DataMahasiswa::create([
      'nim' => $request->nim,
      'nama_lengkap' => $request->name,
      'token' => $token,

      'user_id' => $user->id,
      'no_kamar' => $request->no_kamar,
      'nama_mabna' => $request->nama_mabna,
      'no_hp' => $request->no_hp,
      'link_ktmm' =>$_SERVER['HTTP_HOST'].'/storage/'.$path,
      ]);

      DB::commit(); // Semua berhasil
      return response()->json(['message' => 'Data berhasil disimpan',
      'access_token' => $token,
      ], 200);

      } catch (\Exception $e) {
      DB::rollBack(); // Gagal, batalkan semuanya
      return response()->json(['message' => 'Terjadi kesalahan: '.$e->getMessage()], 500);
      }

      }





// Login
   public function login(Request $request)
   {
   $validator = Validator::make($request->all(), [
   'email' => 'required|email',
   'password' => 'required'
   ]);

   if ($validator->fails()) {
   return response()->json($validator->errors(), 422);
   }

   $mahasiswa = User::where('email', $request->email)->first();


   if (!$mahasiswa) {
   return response()->json(['message' => 'email not found'], 404);
   }


   if (!$mahasiswa || !Hash::check($request->password, $mahasiswa->password)) {
   return response()->json(['message' => 'password salah'], 401);
   }

   // Hapus token sebelumnya (jika ada)
   $mahasiswa->tokens()->delete();

   // Buat token baru
   $token = $mahasiswa->createToken('auth_token')->plainTextToken;

   // Simpan token ke tabel data_mahasiswa
   $mahasiswa->update(['token' => $token]);

   return response()->json([
   'message' => 'Login berhasil',
   'access_token' => $token,
   'token_type' => 'Bearer',
   ]);
   }


  public function logout(Request $request)
  {
  // Hapus token yang aktif
  $request->user()->currentAccessToken()->delete();

  return response()->json([
  'message' => 'Logout berhasil'
  ]);
  }






public function forgotPasswordVerifyEmail(Request $request)
{
$validator = Validator::make($request->all(), [
'email' => 'required|email',
]);

if ($validator->fails()) {
return response()->json($validator->errors(), 422);
}

$user = User::where('email', $request->email)->first();

if (!$user) {
return response()->json(['message' => 'Email not found'], 404);
}

// Buat token random
$token = Str::random(64);

// Simpan token ke tabel password_resets
DB::table('password_reset_tokens')->updateOrInsert(
['email' => $user->email],
[
'token' => $token,
'created_at' => now()
]
);

// Kirim email pakai Mailable
Mail::to($user->email)->send(new resendlink($token, $user->email));

return response()->json(['message' => 'Link reset password telah dikirim']);
}

public function RedirectToResetPaswword( $token,$email)
{

// Cek token di tabel password_resets
$resetToken = DB::table('password_reset_tokens')->where('token', $token)->first();
if (!$resetToken) {
return response()->json(['message' => 'Invalid token'], 400);
}
// Cek apakah token sudah kadaluarsa (misalnya 60 menit)
$createdAt =Carbon::parse($resetToken->created_at);
$expiresAt = $createdAt->addMinutes(60);
if (Carbon::now()->greaterThan($expiresAt)) {
DB::table('password_reset_tokens')->where('token', $token)->delete();
return response()->json(['message' => 'Token expired'], 400);
}

return view('resetpassword',['token' => $token,'email' => $email]);



}







///// Reset Password
public function resetpassword(Request $request)
{



$validator = Validator::make($request->all(), [
'email' => 'required|email',
'token' => 'required|string',
'password' => 'required|string|min:6|confirmed',
]);
if ($validator->fails()) {
return response()->json($validator->errors(), 422);

}
$resetToken = DB::table('password_reset_tokens')->where('token', $request->token)->first();

if (!$resetToken) {
return response()->json(['message' => 'Invalid token'], 400);
}

// Cek apakah token sudah kadaluarsa (misalnya 60 menit)
$createdAt = Carbon::parse($resetToken->created_at);
$expiresAt = $createdAt->addMinutes(60);
if (Carbon::now()->greaterThan($expiresAt)) {
DB::table('password_reset_tokens')->where('token', $request->token)->delete();
return response()->json(['message' => 'Token expired'], 400);
}


// Update status email terverifikasi
$user = User::where('email', $resetToken->email)->first();
if (!$user) {
return response()->json(['message' => 'Email salah'], 404);
}





$affected = DB::table('users')
->where('email', $resetToken->email)
->update(['password' => Hash::make($request->password),'email_verified_at' => now()]);




if ($affected) {
// Jika berhasil update
DB::table('password_reset_tokens')->where('token', $request->token)->delete();
return response()->json(['message' => 'Password updated successfully']);
} else {
// Jika tidak ada perubahan (misalnya email tidak ditemukan)
return response()->json(['message' => 'Failed to update password'], 400);
}

}







}
