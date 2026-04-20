<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
<style> body { font-family: 'Inter', sans-serif; } </style>

<script>
    // Global Toast Notification
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('msg')) {
        const msg = urlParams.get('msg');
        const toast = document.createElement('div');
        toast.className = "fixed bottom-10 right-10 bg-indigo-600 text-white px-8 py-4 rounded-2xl shadow-2xl z-50 animate__animated animate__bounceInUp font-bold";
        toast.innerHTML = `🚀 ${msg.toUpperCase()} SUCCESSFUL`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.replace('animate__bounceInUp', 'animate__fadeOutDown');
            setTimeout(() => toast.remove(), 1000);
        }, 3000);
    }
</script>