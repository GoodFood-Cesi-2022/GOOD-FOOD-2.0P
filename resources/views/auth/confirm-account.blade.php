<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('account.confirm.save') }}">
            @csrf

            <x-input type="hidden" name="token" :value="$token" />

            <!-- Firstname -->
            <div>
                <x-label for="firstname" :value="__('firstname')" />

                <x-input id="firstname" class="block mt-1 w-full opacity-75" type="text" name="firstname" :value="$user->firstname" required autofocus readonly />
            </div>


            <!-- Lastname -->
            <div class="mt-4">
                <x-label for="lastname" :value="__('lastname')" />

                <x-input id="lastname" class="block mt-1 w-full opacity-75" type="text" name="lastname" :value="$user->lastname" required readonly />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full opacity-75" type="email" name="email" :value="$user->email" required readonly />
            </div>


            <!-- Phone -->
            <div class="mt-4">
                <x-label for="phone" :value="__('phone')" />

                <x-input id="phone" class="block mt-1 w-full opacity-75" type="text" name="phone" :value="$user->phone" readonly />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
