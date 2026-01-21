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

    /* Chrome / Edge: sembunyikan icon mata & autofill */
    input[type="password"]::-webkit-credentials-auto-fill-button,
    input[type="password"]::-webkit-textfield-decoration-container,
    input[type="password"]::-webkit-clear-button,
    input[type="password"]::-webkit-inner-spin-button {
      display: none !important;
      visibility: hidden !important;
      pointer-events: none !important;
    }

    /* Edge lama / IE */
    input::-ms-reveal,
    input::-ms-clear {
      display: none;
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
        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" width="70" class="object-contain" />
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

      @if ($errors->any())
      <div class="w-4/5 mx-auto mb-4 p-3 bg-red-100 border border-red-300 text-red-700 text-sm rounded-lg text-center">
        {{ $errors->first() }}
      </div>
      @endif


      <!-- Form Login -->
      <form method="POST" action="{{ route('login.web') }}" class="space-y-6 -mt-2">
        @csrf

        <!-- Email -->
        <div class="w-4/5 mx-auto">
          <label class="block text-xs font-semibold text-gray-600 mb-1">
            Email
          </label>

          <div
            class="flex items-center gap-2 border border-gray-300 rounded-xl px-3 py-2.5
             focus-within:ring-2 focus-within:ring-[#697DCD] focus-within:border-transparent
             transition">
            <svg
              viewBox="0 0 24 24"
              class="w-5 h-5 text-gray-400"
              fill="none"
              xmlns="http://www.w3.org/2000/svg">

              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M3.75 5.25L3 6V18L3.75 18.75H20.25L21 18V6L20.25 5.25H3.75ZM4.5 7.6955V17.25H19.5V7.69525L11.9999 14.5136L4.5 7.6955ZM18.3099 6.75H5.68986L11.9999 12.4864L18.3099 6.75Z"
                fill="currentColor" />
            </svg>

            <!-- Input -->
            <input
              type="email"
              name="email"
              placeholder="email@contoh.com"
              class="flex-1 bg-transparent outline-none text-sm placeholder-gray-400"
              required />
          </div>
        </div>

        <!-- Password -->
        <div class="w-4/5 mx-auto">
          <label class="block text-xs font-semibold text-gray-600 mb-1">
            Kata Sandi
          </label>

          <div
            class="flex items-center gap-2 border border-gray-300 rounded-xl px-3 py-2.5
           focus-within:ring-2 focus-within:ring-[#697DCD] focus-within:border-transparent
           transition">

            <!-- Lock Icon -->
            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24">
              <path d="M7 10V8a5 5 0 0110 0v2"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" />
              <rect x="4" y="10" width="16" height="11" rx="2"
                stroke="currentColor" stroke-width="2" />
            </svg>

            <!-- Input -->
            <input
              id="password"
              type="password"
              name="password"
              placeholder="••••••••"
              autocomplete="new-password"
              autocorrect="off"
              autocapitalize="off"
              spellcheck="false"
              class="flex-1 bg-transparent outline-none text-sm placeholder-gray-400"
              required />

            <!-- Toggle -->
            <button
              type="button"
              onclick="togglePassword(this)"
              class="text-xs font-medium text-gray-400 hover:text-gray-600 transition select-none">
              Lihat
            </button>
          </div>
        </div>

        <!-- Tombol Masuk -->
        <button
          type="submit"
          class="w-4/5 mx-auto block py-2.5 bg-[#697DCD] hover:bg-[#5E72C3]
           text-white text-sm font-semibold rounded-xl transition shadow-sm">
          Masuk
        </button>
      </form>

      <script>
        function togglePassword(btn) {
          const input = document.getElementById('password');
          const isHidden = input.type === 'password';

          input.type = isHidden ? 'text' : 'password';
          btn.textContent = isHidden ? 'Sembunyikan' : 'Lihat';
        }
      </script>


    </div>

    <!-- Kanan: Ilustrasi -->
    <div class="w-full md:w-1/2 bg-white rounded-3xl overflow-hidden flex flex-col justify-center p-8">
      <div class="flex justify-end -mr-8">
        <img src="{{ asset('assets/images/dashboard.png') }}" alt="Dashboard preview"
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