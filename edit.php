<body class="bg-gradient-to-tr from-indigo-100 to-blue-100 h-screen flex items-center justify-center p-4">
    <div class="animate__animated animate__zoomIn bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-white">
        <div class="text-center mb-8">
            <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-gray-800">Edit Details</h2>
        </div>
        
        <form action="actions/update.php" method="POST">
             <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-200 active:scale-95">
                 Update Record
             </button>
        </form>
    </div>
</body>