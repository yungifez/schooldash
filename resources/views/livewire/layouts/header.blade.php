<header class="bg-white dark:bg-gray-800 shadow-md dark:shadow-white h-16 w-full flex justify-between items-center py-2 dark:text-white border-b-2 dark:border-white">
    <div class="flex items-center">
        <a href="#" class="text-2xl mx-3 dark:text-white text-gray-700 px-6" @click="menuOpen = !menuOpen">
            <p class="sr-only">Menu</p>
            <i class="fa fa-bars" aria-hidden="true"></i>
        </a>
        <a href="{{route('home')}}" class="hidden md:flex items-center justify-center">
            <img src="{{config('adminlte.logo_img')}}" alt="" class="rounded-full w-14 h-14 border border-gray-200 shadow-lg">
            <h1 class="text-lg font-semibold mx-3 text-center capitalize">{{config('app.name')}}</h1>
        </a>
    </div>
    <div class="flex justify-evenly items-center gap-6 px-5 h-full" x-data="{'dropDownOpen': false}">
        <button>
            <i class="far fa-moon text-xl" aria-hidden="true" id="dark-mode-switch"></i>
            <p class="sr-only">Dark mode</p>
        </button>
        <button class="h-full"  @click="dropDownOpen = !dropDownOpen">
            <div class="flex items-center h-full">
                <img src="{{auth()->user()->defaultProfilePhotoUrl()}}" alt="" class="rounded-full w-10 h-10 border border-gray-200 shadow-md">
                <p class="hidden lg:block px-2"  >{{auth()->user()->name}}</p>
            </div>
        </button>
        <div class="absolute bg-purple-500 top-16 w-5/6 border  md:w-2/6 lg:w-1/5 shadow-md right-2 flex flex-col items-center justify-center rounded p-4 text-white" x-show="dropDownOpen" x-transition>
            <img src="{{auth()->user()->defaultProfilePhotoUrl()}}" alt="" class="rounded-full w-20 h-20 border border-gray-200 shadow-md">
            <h2 class="text-lg  font-bold">{{auth()->user()->name}}</h2>
            <p class="text-center">
                @isset(auth()->user()->school)
                    Academic year: {{auth()->user()->school->academicYear->name()}} <br>
                    Semester: {{auth()->user()->school->semester->name}}
                @endif
            </p>
            <form action="{{route('logout')}}" class="w-full" method="POST">
                @csrf
                <button href="" class="w-full bg-white text-gray-900 p-3 mt-3 text-center"><i class="fa fa-power-off text-red-700 px-2" aria-hidden="true"></i>Log out</button>
            </form>
        </div>
    </div>
</header>