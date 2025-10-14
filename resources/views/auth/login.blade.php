<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - ManagementHub</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font: Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: "Inter", sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-8">

  <!-- Container Utama -->
  <div class="flex flex-col md:flex-row gap-8 w-full max-w-7xl min-h-[900px]">

    <!-- Kiri: Form Login -->
    <div class="w-full md:w-1/2 bg-white rounded-3xl p-8 flex flex-col justify-center relative border border-black/10 shadow-sm">

      <!-- Logo -->
      <div class="absolute top-5 left-5 flex items-center">
        <img src="{{ asset('storage/images/logo.png') }}" alt="logo" width="70" class="object-contain" />
        <h5 class="font-semibold text-gray-800 opacity-80 text-base leading-none -ml-[6px]">
          ManagementHub
        </h5>
      </div>

      <!-- Judul -->
      <div class="mt-20 text-center">
        <h4 class="text-[18px] text-gray-800 mb-2">
          Selamat datang di
          <span class="font-semibold text-gray-800 opacity-70">ManagementHub</span>
        </h4>
        <p class="text-[12px] text-gray-500 mb-6 leading-relaxed w-4/5 mx-auto">
          Masuk untuk mengelola perangkat jaringan kantor Anda secara efisien dan terpusat.
        </p>
      </div>

      <!-- Tombol Tab -->
      <div class="flex border border-gray-300 rounded-xl overflow-hidden mb-8 text-xs w-8/12 mx-auto">
        <a href="/login"
          class="flex-1 h-10 flex items-center justify-center font-medium text-black bg-[#E5E8F7]">
          Masuk
        </a>
        <a href="/register"
          class="flex-1 h-10 flex items-center justify-center font-medium bg-white text-gray-700 hover:bg-gray-100 transition">
          Daftar
        </a>
      </div>

      @if ($errors->any())
      <div class="w-4/5 mx-auto mb-4 p-3 bg-red-100 border border-red-300 text-red-700 text-sm rounded-lg text-center">
        {{ $errors->first() }}
      </div>
      @endif


      <!-- Form Login -->
      <form method="POST" action="{{ route('login.web') }}" class="space-y-5 -mt-2">
        @csrf

        <!-- Email -->
        <div class="w-4/5 mx-auto">
          <label class="block text-xs font-medium text-gray-700 mb-1">Email *</label>
          <div
            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-[#697DCD] focus-within:border-transparent">
            <div class="pl-3 pr-2 text-gray-400 flex items-center justify-center">
              <svg viewBox="0 0 24 24" width="20" height="20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M3.75 5.25L3 6V18L3.75 18.75H20.25L21 18V6L20.25 5.25H3.75ZM4.5 7.6955V17.25H19.5V7.69525L11.9999 14.5136L4.5 7.6955ZM18.3099 6.75H5.68986L11.9999 12.4864L18.3099 6.75Z"
                  fill="currentColor" />
              </svg>
            </div>
            <input type="email" name="email" placeholder="Masukkan Alamat Email Anda"
              class="flex-1 py-2 text-sm outline-none" required />
          </div>
        </div>

        <!-- Password -->
        <div class="w-4/5 mx-auto">
          <label class="block text-xs font-medium text-gray-700 mb-1">Kata Sandi *</label>
          <div
            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-[#697DCD] focus-within:border-transparent">
            <div class="pl-3 pr-2 text-gray-400 flex items-center justify-center">
              <svg viewBox="0 0 24 24" width="20" height="20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M7 10.0288C7.47142 10 8.05259 10 8.8 10H15.2C15.9474 10 16.5286 10 17 10.0288M7 10.0288C6.41168 10.0647 5.99429 10.1455 5.63803 10.327C5.07354 10.6146 4.6146 11.0735 4.32698 11.638C4 12.2798 4 13.1198 4 14.8V16.2C4 17.8802 4 18.7202 4.32698 19.362C4.6146 19.9265 5.07354 20.3854 5.63803 20.673C6.27976 21 7.11984 21 8.8 21H15.2C16.8802 21 17.7202 21 18.362 20.673C18.9265 20.3854 19.3854 19.9265 19.673 19.362C20 18.7202 20 17.8802 20 16.2V14.8C20 13.1198 20 12.2798 19.673 11.638C19.3854 11.0735 18.9265 10.6146 18.362 10.327C18.0057 10.1455 17.5883 10.0647 17 10.0288M7 10.0288V8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8V10.0288"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round" />
              </svg>
            </div>
            <input type="password" name="password" placeholder="Masukkan Kata Sandi Anda"
              class="flex-1 py-2 text-sm outline-none" required />
          </div>
        </div>

        <!-- Tombol Masuk -->
        <button type="submit"
          class="w-4/5 mx-auto block py-2 bg-[#697DCD] hover:bg-[#5E72C3] text-white text-sm font-medium rounded-lg transition">
          Masuk
        </button>
      </form>
    </div>

    <!-- Kanan: Ilustrasi -->
    <div class="w-full md:w-1/2 bg-white rounded-3xl overflow-hidden flex flex-col justify-center p-8">
      <div class="flex justify-end -mr-8">
        <img src="{{ asset('storage/images/dashboard.png') }}" alt="Dashboard preview"
          class="w-full md:w-11/12 h-auto object-cover rounded-l-2xl mb-9 border border-black/10">
      </div>
      <div class="px-0 md:px-4 pb-6 text-left">
        <h5 class="text-[17px] font-bold text-black/70 mb-2">
          Pantau dan Maintenance Komponen <br /> Jaringan dengan Mudah
        </h5>
        <p class="text-[12px] text-black/70 leading-relaxed">
          ManagementHub membantu Anda mengelola semua komponen jaringan kantor secara efisien. Setiap kerusakan dapat
          langsung dilaporkan dan ditangani, sementara jadwal maintenance rutin dipantau otomatis.
        </p>
      </div>
    </div>
  </div>
</body>

</html>