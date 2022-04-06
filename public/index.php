<?php
require_once __DIR__.'/../src/bootstrap.php';

?>

<!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>DDD PRS</title>
     <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.1/dist/flowbite.min.css" />

 </head>

 <body class="bg-gray-50 flex flex-col h-screen justify-end">

     <nav class=" bg-white py-6 border-b border-gray-200 px-2 sm:px-4 py-2.5 rounded-lg dark:bg-gray-800">
         <div class="container flex flex-wrap justify-between items-center mx-auto">
             <a href="index.php" class="flex items-center">
                 <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-6 sm:h-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                 </svg>
                 <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">DDD PRS</span>
             </a>
             <button data-collapse-toggle="mobile-menu" type="button" class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu" aria-expanded="false">
                 <span class="sr-only">Open main menu</span>
                 <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                 </svg>
                 <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                 </svg>
             </button>
             <div class="hidden w-full md:block md:w-auto" id="mobile-menu">
                 <ul class="flex flex-col mt-4 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium">
                     <li>
                         <a href="index.php" class="block py-2 pr-4 pl-3  rounded md:bg-transparent text-blue-700 md:p-0" aria-current="page">Home</a>
                     </li>
                     <li>
                         <a href="login.php" class="block py-2 pr-4 pl-3 rounded md:bg-transparent text-blue-700  md:p-0" aria-current="page">Login</a>
                     </li>

                 </ul>
             </div>
         </div>
     </nav>

     <main class="">
         <?= "I'm home :)"; ?>
     </main>
     <center>
         <footer class="fixed w-full bottom-0 p-4 bg-white rounded-lg shadow md:p-6 text-center ">
             <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">Productivity Reporting System © 2022 <a href="index.php" class="hover:underline">by Digital Divide Data</a>. All Rights Reserved.
             </span>
         </footer>
     </center>
     <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
 </body>

 </html>