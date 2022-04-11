<!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title><?=$code;?></title>
     <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.1/dist/flowbite.min.css" />
</head>

<body>
    <div class="container text-center mt-8 py-12 mx-auto">
        <h1 class="text-4xl font-semibold mb-4"><?=$code;?></h1>
        <p class="text-gray-700 mb-10"><?=$message;?></p>
        <a href="javascript:history.back()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 tracking-wider uppercase text-sm">&larr; Go back</a>
    </div>
    <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
</body>

</html>