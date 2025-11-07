@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 font-sora">Messages</h1>
    <p class="text-gray-600 mt-1">Communicate with your patients</p>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden h-[calc(100vh-200px)] flex">
    <!-- Conversations List -->
    <div class="w-1/3 border-r border-gray-200 overflow-y-auto">
        <div class="p-4 border-b border-gray-200">
            <input type="text" placeholder="Search patients..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>

        <div class="divide-y divide-gray-200">
            <div class="p-4 hover:bg-gray-50 cursor-pointer border-l-4 border-primary bg-primary/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        JD
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <p class="font-semibold text-gray-800 truncate">John Doe</p>
                            <span class="text-xs text-gray-500">2m ago</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Thank you for the prescription...</p>
                    </div>
                </div>
            </div>

            <div class="p-4 hover:bg-gray-50 cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        JS
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <p class="font-semibold text-gray-800 truncate">Jane Smith</p>
                            <span class="text-xs text-gray-500">1h ago</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">When should I take the medication?</p>
                    </div>
                </div>
            </div>

            <div class="p-4 hover:bg-gray-50 cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        AR
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <p class="font-semibold text-gray-800 truncate">Ahmad Rizki</p>
                            <span class="text-xs text-gray-500">3h ago</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">My blood pressure is 140/90 today</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col">
        <!-- Chat Header -->
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white font-semibold">
                    JD
                </div>
                <div>
                    <p class="font-bold text-gray-800">John Doe</p>
                    <p class="text-xs text-gray-500">Last seen 2 minutes ago</p>
                </div>
            </div>
            <button class="px-4 py-2 text-sm text-primary hover:bg-primary/5 rounded-lg">
                View Patient Profile
            </button>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <!-- Received Message -->
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                    JD
                </div>
                <div class="flex-1">
                    <div class="bg-gray-100 rounded-lg p-3 max-w-md">
                        <p class="text-sm text-gray-800">Hello Doctor, I wanted to ask about the new medication you prescribed.</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">10:30 AM</p>
                </div>
            </div>

            <!-- Sent Message -->
            <div class="flex gap-3 justify-end">
                <div class="flex-1 flex flex-col items-end">
                    <div class="bg-primary rounded-lg p-3 max-w-md">
                        <p class="text-sm text-white">Hi John! Of course, what would you like to know?</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">10:32 AM</p>
                </div>
                <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                    DS
                </div>
            </div>

            <!-- Received Message -->
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                    JD
                </div>
                <div class="flex-1">
                    <div class="bg-gray-100 rounded-lg p-3 max-w-md">
                        <p class="text-sm text-gray-800">Should I take it before or after meals?</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">10:35 AM</p>
                </div>
            </div>

            <!-- Sent Message -->
            <div class="flex gap-3 justify-end">
                <div class="flex-1 flex flex-col items-end">
                    <div class="bg-primary rounded-lg p-3 max-w-md">
                        <p class="text-sm text-white">Take it 30 minutes after meals, twice daily as prescribed. Make sure to stay hydrated.</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">10:36 AM</p>
                </div>
                <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                    DS
                </div>
            </div>

            <!-- Received Message -->
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                    JD
                </div>
                <div class="flex-1">
                    <div class="bg-gray-100 rounded-lg p-3 max-w-md">
                        <p class="text-sm text-gray-800">Thank you for the clarification, Doctor! I'll follow your instructions.</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">10:38 AM</p>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="p-4 border-t border-gray-200">
            <form class="flex gap-3">
                <input type="text" placeholder="Type your message..." class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold">
                    Send
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
