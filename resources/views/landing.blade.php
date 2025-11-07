<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medwell - Digital Healthcare Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sora', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 to-purple-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="md:flex">
                <!-- Left Side - Info -->
                <div class="md:w-1/2 bg-gradient-to-br from-purple-600 to-purple-800 p-12 text-white">
                    <h1 class="text-4xl font-bold mb-4">🏥 Medwell</h1>
                    <p class="text-xl mb-6">Digital Healthcare Platform</p>
                    <p class="text-purple-100 mb-8">Comprehensive patient monitoring system with wearable integration, vital signs tracking, and medication management.</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Wearable Device Integration</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Real-time Vital Signs Monitoring</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Medication Management & Consent</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Health Alerts & Notifications</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login -->
                <div class="md:w-1/2 p-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
                    <p class="text-gray-600 mb-8">Please login to continue</p>

                    <a href="{{ route('login') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center font-semibold py-4 px-6 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105 mb-4">
                        Login to Dashboard
                    </a>

                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4">Quick Access:</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                                <span class="font-medium text-gray-700">Super Admin</span>
                                <code class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">superadmin@medwell.id</code>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                                <span class="font-medium text-gray-700">Admin</span>
                                <code class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">admin@biofarma.co.id</code>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                                <span class="font-medium text-gray-700">Clinician</span>
                                <code class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">sarah.cardio@biofarma.co.id</code>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                                <span class="font-medium text-gray-700">Patient</span>
                                <code class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">john.doe@email.com</code>
                            </div>
                            <p class="text-xs text-gray-500 text-center mt-3">All passwords: <strong>password123</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-4 right-4 bg-white px-4 py-2 rounded-lg shadow-lg text-sm text-gray-600">
        <strong class="text-purple-600">Medwell</strong> v1.0.0 | Powered by Bio Farma
    </div>
</body>
</html>
