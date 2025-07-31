<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informações do Perfil</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Atualize as informações do perfil e endereço de email da sua conta.
                    </p>
                    
                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')
                        
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nome</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" />
                            @if($errors->has('name'))
                                <div class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" />
                            @if($errors->has('email'))
                                <div class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Salvar
                            </button>
                            
                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-gray-600 dark:text-gray-400">Salvo.</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Atualizar Senha</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Certifique-se de que sua conta esteja usando uma senha longa e aleatória para permanecer segura.
                    </p>
                    
                    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('put')
                        
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Senha Atual</label>
                            <input type="password" name="current_password" 
                                   class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" />
                            @if($errors->updatePassword->has('current_password'))
                                <div class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">{{ $errors->updatePassword->first('current_password') }}</div>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nova Senha</label>
                            <input type="password" name="password" 
                                   class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" />
                            @if($errors->updatePassword->has('password'))
                                <div class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">{{ $errors->updatePassword->first('password') }}</div>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Confirmar Senha</label>
                            <input type="password" name="password_confirmation" 
                                   class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" />
                            @if($errors->updatePassword->has('password_confirmation'))
                                <div class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Salvar
                            </button>
                            
                            @if (session('status') === 'password-updated')
                                <p class="text-sm text-gray-600 dark:text-gray-400">Salvo.</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Excluir Conta</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente apagados.
                    </p>
                    
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        Para testes, esta funcionalidade está desabilitada.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
