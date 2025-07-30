<form id="filterForm" method="GET" action="list-product.php" class="space-y-0">
  <div class="sticky top-20 py-4 overflow-y-auto rounded">
    <ul class="space-y-0 mb-4">
      <!-- PLANT TYPE -->
      <li>
        <button
          type="button"
          class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 border-t border-gray-200 hover:bg-gray-100 group"
          aria-controls="dropdown-1"
          data-collapse-toggle="dropdown-1">
          <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>PLANT TYPE</span>
          <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <ul id="dropdown-1" class="hidden py-2 space-y-2">
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="plant_type[]" value="Indoor Plants" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>Indoor Plants</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="plant_type[]" value="Outdoor Plants" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>Outdoor Plants</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="plant_type[]" value="Succulents" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>Succulents</span>
            </label>
          </li>
        </ul>
      </li>
      <!-- PRICE RANGE -->
      <li>
        <button
          type="button"
          class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 border-t border-gray-200 hover:bg-gray-100 group"
          aria-controls="dropdown-2"
          data-collapse-toggle="dropdown-2">
          <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>PRICE RANGE</span>
          <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <ul id="dropdown-2" class="hidden py-2 space-y-2">
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="price_range[]" value="under_20" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>Under $20</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="price_range[]" value="20_50" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>$20 - $50</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="price_range[]" value="50_100" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>$50 - $100</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="price_range[]" value="above_100" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>Above $100</span>
            </label>
          </li>
        </ul>
      </li>
      <!-- PLANT SIZE -->
      <li>
        <button
          type="button"
          class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 border-t border-gray-200 hover:bg-gray-100 group"
          aria-controls="dropdown-3"
          data-collapse-toggle="dropdown-3">
          <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>PLANT SIZE</span>
          <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <ul id="dropdown-3" class="hidden py-2 space-y-2">
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="plant_size[]" value='XS (5"-12")' class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>XS (5"-12")</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="plant_size[]" value='SM (7"-18")' class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>SM (7"-18")</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="plant_size[]" value='MD (1-2 FT)' class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>MD (1-2 FT)</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="plant_size[]" value='LG (1.5-2.5 FT)' class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>LG (1.5-2.5 FT)</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="plant_size[]" value='XL (2-3 FT)' class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>XL (2-3 FT)</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="plant_size[]" value='XXL (3-5 FT)' class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>XXL (3-5 FT)</span>
            </label>
          </li>
        </ul>
      </li>
      <!-- PET FRIENDLY -->
      <li>
        <button
          type="button"
          class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 border-t border-gray-200 hover:bg-gray-100 group"
          aria-controls="dropdown-4"
          data-collapse-toggle="dropdown-4">
          <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>PET FRIENDLY</span>
          <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <ul id="dropdown-4" class="hidden py-2 space-y-2">
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="pet_friendly[]" value="YES" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>YES</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="pet_friendly[]" value="NO" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>NO</span>
            </label>
          </li>
        </ul>
      </li>
      <!-- PRODUCT LABELS -->
      <li>
        <button
          type="button"
          class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 border-t border-b border-gray-200 hover:bg-gray-100 group"
          aria-controls="dropdown-5"
          data-collapse-toggle="dropdown-5">
          <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>PRODUCT LABELS</span>
          <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <ul id="dropdown-5" class="hidden py-2 space-y-2">
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="product_label[]" value="BEST SELLER" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>BEST SELLER</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="product_label[]" value="NEW ARRIVAL" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>NEW ARRIVAL</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="product_label[]" value="LIMITED STOCK" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>LIMITED STOCK</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="product_label[]" value="OUT OF STOCK" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>OUT OF STOCK</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="product_label[]" value="POPULAR" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>POPULAR</span>
            </label>
          </li>
        </ul>
      </li>
      <!-- DIFFICULTY -->
      <li>
        <button
          type="button"
          class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 border-t border-b border-gray-200 hover:bg-gray-100 group"
          aria-controls="dropdown-7"
          data-collapse-toggle="dropdown-7">
          <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>DIFFICULTY</span>
          <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <ul id="dropdown-7" class="hidden py-2 space-y-2">
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="difficulty[]" value="NO-FUSS" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>NO-FUSS</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="difficulty[]" value="MODERATE" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>MODERATE</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="difficulty[]" value="EASY" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>EASY</span>
            </label>
          </li>
        </ul>
      </li>
      <!-- RATING -->
      <li>
        <button
          type="button"
          class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 border-t border-b border-gray-200 hover:bg-gray-100 group"
          aria-controls="dropdown-6"
          data-collapse-toggle="dropdown-6">
          <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>RATING </span>
          <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <ul id="dropdown-6" class="hidden py-2 space-y-2">
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="rating[]" value="5" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>5 Stars</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="rating[]" value="4" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>4 Stars & Above</span>
            </label>
          </li>
          <li>
            <label class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 pl-11">
              <input type="checkbox" name="rating[]" value="3" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300 mr-3" />
              <span>3 Stars & Above</span>
            </label>
          </li>
        </ul>
      </li>
    </ul>
    <div class="pt-4 px-4 border-gray-200">
      <button type="submit" class="w-full inline-block text-center border-primary border text-primary py-2.5 px-6 rounded-full text-lg font-normal transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">
        APPLY FILTERS
      </button>
      <a href="list-product.php" class="w-full inline-block text-center border-primary border text-primary py-2.5 px-6 rounded-full text-lg font-normal transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg mt-2">
        CLEAR FILTERS
      </a>
    </div>
  </div>
</form>
