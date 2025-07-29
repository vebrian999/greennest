

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GreenNest | plant store</title>
    <link rel="stylesheet" href="./src/output.css" />
    <link rel="stylesheet" href="./src/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet" />
    <link rel="icon" href="./src/img/favicon.ico" type="image/x-icon" />
      <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
  
  <aside class="sticky top-20 py-4 overflow-y-auto rounded">
    <ul class="space-y-0 mb-4">
      <li>
        <button type="button" class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 border-t border-gray-200 hover:bg-gray-100 group" aria-controls="dropdown-1" data-collapse-toggle="dropdown-1">
          <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>PRODUCT TYPE</span>
          <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <ul id="dropdown-1" class="hidden py-2 space-y-2 transition-all duration-300">
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>Pots and Planters</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>Plant Care Tools</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>Plant Care Supplies</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>Plant Accessories</span></label></li>
        </ul>
      </li>
      <li>
        <button type="button" class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 border-t border-gray-200 hover:bg-gray-100 group" aria-controls="dropdown-2" data-collapse-toggle="dropdown-2">
          <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>SIZE</span>
          <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <ul id="dropdown-2" class="hidden py-2 space-y-2">
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>10"</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>6"-10"</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>10"-14"</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>Small</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>Large</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>One Size</span></label></li>
        </ul>
      </li>
      <li>
        <button type="button" class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 border-t border-gray-200 hover:bg-gray-100 group" aria-controls="dropdown-3" data-collapse-toggle="dropdown-3">
          <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>PRICE</span>
          <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <ul id="dropdown-3" class="hidden py-2 space-y-2">
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>Under $50</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>$50-$100</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>$100-$150</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>$150-$200</span></label></li>
          <li><label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11"><input type="checkbox" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" /><span>$200 & Above</span></label></li>
        </ul>
      </li>
    </ul>
    <div class="pt-4 px-4 border-gray-200">
      <a href="#" class="w-full inline-block text-center border-primary border text-primary py-2.5 px-6 rounded-full text-lg font-normal transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">
        CLEAR FILTERS
      </a>
    </div>
  </aside>

  <script src="../src/script.js"></script>
  </body>
</html>
