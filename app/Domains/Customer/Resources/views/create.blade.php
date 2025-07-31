@extends('shared::layouts.app')

@section('title', __('customers.create') . ' - UPMANAGER')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <div>
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-500">
                        {{ __('dashboard.title') }}
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('customers.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        {{ __('customers.title') }}
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">{{ __('customers.create') }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('customers.create_title') }}</h1>
            <p class="mt-1 text-sm text-gray-600">{{ __('customers.fill_info') }}</p>
        </div>
        
        <form action="{{ route('customers.store') }}" method="POST" class="px-6 py-6 space-y-6">
            @csrf
            
            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('customers.name') }} <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                    placeholder="{{ __('customers.name_placeholder') }}"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('customers.status') }} <span class="text-red-500">*</span>
                </label>
                <select 
                    name="status" 
                    id="status" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror"
                    required
                >
                    <option value="">{{ __('customers.select_status') }}</option>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>{{ __('customers.active') }}</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __('customers.inactive') }}</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contatos -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-medium text-gray-700">
                        {{ __('customers.contacts') }}
                    </label>
                    <button type="button" 
                            id="add-contact" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('customers.add_contact') }}
                    </button>
                </div>
                
                <div id="contacts-container" class="space-y-4">
                    @if(old('contacts'))
                        @foreach(old('contacts') as $index => $contact)
                            <div class="contact-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-700">{{ __('customers.contacts') }} #{{ $index + 1 }}</h4>
                                    <button type="button" class="remove-contact text-red-600 hover:text-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('customers.contact_name') }} <span class="text-red-500">*</span></label>
                                        <input type="text" 
                                               name="contacts[{{ $index }}][name]" 
                                               value="{{ $contact['name'] ?? '' }}"
                                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('contacts.'.$index.'.name') border-red-300 @enderror"
                                               placeholder="{{ __('customers.contact_name_placeholder') }}">
                                        @error('contacts.'.$index.'.name')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('customers.contact_phone') }}</label>
                                        <input type="text" 
                                               name="contacts[{{ $index }}][phone]" 
                                               value="{{ $contact['phone'] ?? '' }}"
                                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('contacts.'.$index.'.phone') border-red-300 @enderror"
                                               placeholder="{{ __('customers.contact_phone_placeholder') }}">
                                        @error('contacts.'.$index.'.phone')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('customers.contact_email') }}</label>
                                        <input type="email" 
                                               name="contacts[{{ $index }}][email]" 
                                               value="{{ $contact['email'] ?? '' }}"
                                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('contacts.'.$index.'.email') border-red-300 @enderror"
                                               placeholder="{{ __('customers.contact_email_placeholder') }}">
                                        @error('contacts.'.$index.'.email')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <div id="no-contacts" class="text-center py-8 text-gray-500 text-sm {{ old('contacts') ? 'hidden' : '' }}">
                    {{ __('customers.no_contacts') }}
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('customers.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('common.cancel') }}
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('customers.create_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing contacts script...');
    
    const addContactBtn = document.getElementById('add-contact');
    const contactsContainer = document.getElementById('contacts-container');
    const noContactsDiv = document.getElementById('no-contacts');
    let contactIndex = {{ count(old('contacts', [])) }};

    console.log('Elements found:', {
        addContactBtn: !!addContactBtn,
        contactsContainer: !!contactsContainer,
        noContactsDiv: !!noContactsDiv,
        contactIndex: contactIndex
    });

    if (!addContactBtn) {
        console.error('Add contact button not found!');
        return;
    }

    // Traduções
    const translations = {
        contacts: '{{ __('customers.contacts') }}',
        contactName: '{{ __('customers.contact_name') }}',
        contactPhone: '{{ __('customers.contact_phone') }}',
        contactEmail: '{{ __('customers.contact_email') }}',
        contactNamePlaceholder: '{{ __('customers.contact_name_placeholder') }}',
        contactPhonePlaceholder: '{{ __('customers.contact_phone_placeholder') }}',
        contactEmailPlaceholder: '{{ __('customers.contact_email_placeholder') }}'
    };

    addContactBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Add contact button clicked!');
        addContact();
    });

    // Delegação de eventos para remover contatos
    contactsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-contact')) {
            console.log('Remove contact clicked');
            e.target.closest('.contact-item').remove();
            updateContactNumbers();
            toggleNoContactsMessage();
        }
    });

    function addContact() {
        console.log('Adding contact with index:', contactIndex);
        
        const contactHtml = `
            <div class="contact-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-medium text-gray-700">${translations.contacts} #${contactIndex + 1}</h4>
                    <button type="button" class="remove-contact text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">${translations.contactName} <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="contacts[${contactIndex}][name]" 
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="${translations.contactNamePlaceholder}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">${translations.contactPhone}</label>
                        <input type="text" 
                               name="contacts[${contactIndex}][phone]" 
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="${translations.contactPhonePlaceholder}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">${translations.contactEmail}</label>
                        <input type="email" 
                               name="contacts[${contactIndex}][email]" 
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="${translations.contactEmailPlaceholder}">
                    </div>
                </div>
            </div>
        `;
        
        contactsContainer.insertAdjacentHTML('beforeend', contactHtml);
        contactIndex++;
        toggleNoContactsMessage();
        
        console.log('Contact added successfully! New contactIndex:', contactIndex);
    }

    function updateContactNumbers() {
        const contactItems = contactsContainer.querySelectorAll('.contact-item');
        contactItems.forEach((item, index) => {
            const title = item.querySelector('h4');
            title.textContent = `${translations.contacts} #${index + 1}`;
        });
    }

    function toggleNoContactsMessage() {
        const hasContacts = contactsContainer.children.length > 0;
        noContactsDiv.classList.toggle('hidden', hasContacts);
        console.log('Toggle no contacts message. Has contacts:', hasContacts);
    }
});
</script>
@endpush 