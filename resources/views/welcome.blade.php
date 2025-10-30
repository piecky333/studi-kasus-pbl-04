<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Finansial - Desain Gabungan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Konfigurasi warna biru kustom jika diperlukan
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Anda bisa mengganti 'blue-600' di bawah dengan kode hex spesifik
                        'primary': tailwind.colors.blue[600],
                        'primary-dark': tailwind.colors.blue[700],
                    }
                }
            }
        }
    </script>
</head>

<body class="h-full">
    <div class="flex h-screen">

        <aside class="w-64 flex-shrink-0 bg-white border-r border-gray-200 flex flex-col">
            
            <div class="h-20 flex items-center justify-center px-6 border-b border-gray-200">
                <span class="text-2xl font-bold text-blue-600">Bankio</span>
                </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                
                <span class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Main Menu</span>
                
                <a class="flex items-center px-3 py-3 rounded-lg bg-gray-900 text-white" 
                   href="#">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25A2.25 2.25 0 0 1 13.5 8.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>
                
                <a class="flex items-center px-3 py-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900" 
                   href="#">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h15.75c.621 0 1.125.504 1.125 1.125v6.75C21 20.496 20.496 21 19.875 21H4.125A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v.375c0 1.036-.84 1.875-1.875 1.875h-.75c-1.036 0-1.875-.84-1.875-1.875v-.375Z" /></svg>
                    <span class="ml-3 font-medium">Analytics</span>
                </a>
                
                <a class="flex items-center px-3 py-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900" 
                   href="#">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h18M16.5 3l4.5 4.5m0 0L16.5 12M21 7.5H3" /></svg>
                    <span class="ml-3 font-medium">Transactions</span>
                </a>

                <span class="block pt-6 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Others</span>
                
                <a class="flex items-center px-3 py-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900" 
                   href="#">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-1.007 1.11-.1.09.542-.56 1.007-1.11.1ZM14.406 3.94c-.09-.542-.56-1.007-1.11-.1-.09.542.56 1.007 1.11.1ZM12 21.75c-1.313 0-2.453-.786-2.94-1.875a10.052 10.052 0 0 1-3.1-2.07c-.09-.091-.182-.183-.27-.275a.96.96 0 0 1 .41-1.63c.41-.12.83-.1 1.22.08.39.18.77.42 1.14.72 1.13 1.01 2.66 1.67 4.33 1.67s3.2- .66 4.33-1.67c.37-.3.75-.54 1.14-.72.39-.18.81-.2 1.22-.08.5.15.6.82.41 1.63-.08.09-.18.18-.27.27a10.05 10.05 0 0 1-3.1 2.07c-.487 1.09-1.627 1.875-2.94 1.875Z" /></svg>
                    <span class="ml-3 font-medium">Rewards</span>
                </a>

            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Welcome, Aubrey Sabina!</h2>
                    <p class="text-sm text-gray-500">Effectively manage your finances with real-time insights.</p>
                </div>

                <div class="flex items-center space-x-6">
                    <button class="text-gray-500 hover:text-gray-800">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    </button>
                    <button class="text-gray-500 hover:text-gray-800">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                    </button>
                    <img class="h-10 w-10 rounded-full object-cover"
                         src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                         alt="Foto Profil">
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-100 p-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500">Main account</p>
                                <h3 class="text-xl font-semibold text-gray-800">NevBank Savings Account</h3>
                                <p class="text-sm text-gray-400">88 1240 7793 7644 3667 0002 9448</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Available funds</p>
                                <span class="text-4xl font-bold text-gray-900">68.789,56 $</span>
                            </div>
                        </div>
                        <div class="mt-8 flex space-x-4">
                            <button class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                                Transfer money
                            </button>
                            <button class="px-6 py-3 bg-gray-100 text-gray-800 rounded-lg font-medium hover:bg-gray-200">
                                Link accounts
                            </button>
                        </div>
                    </div>

                    <div class="lg:col-span-1 bg-blue-600 text-white rounded-xl shadow-lg p-6 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-semibold">Define standing orders</h3>
                            <p class="mt-2 text-blue-100">We'll help you remember about recurring payments. Just set them once!</p>
                        </div>
                        <div class="flex justify-between items-end">
                            <button class="mt-6 px-5 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50">
                                Define standing order
                            </button>
                            <svg class="w-16 h-16 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                        </div>
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl shadow p-5">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/Santander_Logotipo.svg/2560px-Santander_Logotipo.svg.png" alt="Santander" class="h-6 mb-4">
                        <p class="text-sm text-gray-400">88 **** 9448</p>
                        <span class="text-xl font-bold text-gray-900">12.220,65 $</span>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Citibank_logo.svg/2560px-Citibank_logo.svg.png" alt="Citibank" class="h-6 mb-4">
                        <p class="text-sm text-gray-400">45 **** 8854</p>
                        <span class="text-xl font-bold text-gray-900">25.070,65 $</span>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1d/Deutsche_Bank_logo_without_wordmark.svg/2048px-Deutsche_Bank_logo_without_wordmark.svg.png" alt="Deutsche Bank" class="h-6 mb-4">
                        <p class="text-sm text-gray-400">67 **** 0021</p>
                        <span class="text-xl font-bold text-gray-900">570,00 $</span>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2d/Cr%C3%A9dit_Agricole_logo.svg/2560px-Cr%C3%A9dit_Agricole_logo.svg.png" alt="Credit Agricole" class="h-6 mb-4">
                        <p class="text-sm text-gray-400">55 **** 7655</p>
                        <span class="text-xl font-bold text-gray-900">2.680,50 $</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-10">
                    
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-800">Latest transactions</h3>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">See more &rarr;</a>
                        </div>
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200">
                                <li class="py-4 flex justify-between items-center">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <span class="text-sm text-gray-500">Today</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 truncate">Starbucks Cafe</p>
                                            <p class="text-sm text-gray-500 truncate">Card payment</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-semibold text-red-600">- 15,00 $</span>
                                        <span class="ml-2 inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Food</span>
                                    </div>
                                </li>
                                <li class="py-4 flex justify-between items-center">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <span class="text-sm text-gray-500">20.05</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 truncate">Spotify Premium</p>
                                            <p class="text-sm text-gray-500 truncate">Fee</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-semibold text-red-600">- 10,00 $</span>
                                        <span class="ml-2 inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">Entertainment</span>
                                    </div>
                                </li>
                                <li class="py-4 flex justify-between items-center">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <span class="text-sm text-gray-500">20.05</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 truncate">Google Inc.</p>
                                            <p class="text-sm text-gray-500 truncate">Transfer</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-semibold text-green-600">+ 9.500 $</span>
                                        <span class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Salary</span>
                                    </div>
                                </li>
                                </ul>
                        </div>
                    </div>

                    <div class="lg:col-span-1 bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-800">All expenses</h3>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">&rarr;</a>
                        </div>
                        <div class="flex justify-between text-center border-b pb-4">
                            <div>
                                <p class="text-xs text-gray-500">daily</p>
                                <span class="text-lg font-semibold text-gray-900">275,40 $</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">weekly</p>
                                <span class="text-lg font-semibold text-gray-900">1.420,65 $</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">monthly</p>
                                <span class="text-lg font-semibold text-gray-900">8.200,00 $</span>
                            </div>
                        </div>
                        <div class="relative flex justify-center items-center my-6 h-48">
                            <p class="absolute text-3xl font-bold text-gray-900">8.400 $</p>
                             <p class="text-gray-400">(Placeholder untuk Chart)</p>
                        </div>
                        
                        <ul class="space-y-2 text-sm">
                            <li class="flex justify-between items-center">
                                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>Clothes</span>
                                <span class="font-medium text-gray-700">1.200 $</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></span>Education</span>
                                <span class="font-medium text-gray-700">800 $</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>Health</span>
                                <span class="font-medium text-gray-700">1.500 $</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-purple-500 mr-2"></span>Entertainment</span>
                                <span class="font-medium text-gray-700">2.100 $</span>
                            </li>
                            </ul>
                    </div>
                </div>

            </main> </div> </div> </body>
</html>