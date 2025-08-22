<!DOCTYPE html>
<html lang="en" class="font-sans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Kilindi District Traders Management System</title>
    <meta name="description" content="Professional administrative interface for managing trader records and business licenses">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        card: 'hsl(var(--card))',
                        'card-foreground': 'hsl(var(--card-foreground))',
                        border: 'hsl(var(--border))',
                        'muted-foreground': 'hsl(var(--muted-foreground))',
                        sidebar: 'hsl(var(--sidebar))',
                        'sidebar-border': 'hsl(var(--sidebar-border))',
                        'sidebar-foreground': 'hsl(var(--sidebar-foreground))',
                        'sidebar-accent': 'hsl(var(--sidebar-accent))',
                        'sidebar-accent-foreground': 'hsl(var(--sidebar-accent-foreground))',
                        'sidebar-primary': 'hsl(var(--sidebar-primary))',
                        'sidebar-primary-foreground': 'hsl(var(--sidebar-primary-foreground))',
                        accent: 'hsl(var(--accent))',
                        'accent-foreground': 'hsl(var(--accent-foreground))',
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --background: 0 0% 100%;
            --foreground: 222.2 84% 4.9%;
            --card: 0 0% 100%;
            --card-foreground: 222.2 84% 4.9%;
            --border: 214.3 31.8% 91.4%;
            --muted-foreground: 215.4 16.3% 46.9%;
            --sidebar: 210 40% 98%;
            --sidebar-border: 220 13% 91%;
            --sidebar-foreground: 215 25% 27%;
            --sidebar-accent: 216 12% 84%;
            --sidebar-accent-foreground: 215 25% 27%;
            --sidebar-primary: 221 83% 53%;
            --sidebar-primary-foreground: 210 40% 98%;
            --accent: 210 40% 96%;
            --accent-foreground: 222.2 84% 4.9%;
        }
        .dark {
            --background: 222.2 84% 4.9%;
            --foreground: 210 40% 98%;
            --card: 222.2 84% 4.9%;
            --card-foreground: 210 40% 98%;
            --border: 217.2 32.6% 17.5%;
            --muted-foreground: 215 20.2% 65.1%;
            --sidebar: 224 71% 4%;
            --sidebar-border: 216 34% 17%;
            --sidebar-foreground: 213 31% 91%;
            --sidebar-accent: 216 34% 17%;
            --sidebar-accent-foreground: 210 40% 98%;
            --sidebar-primary: 221 83% 53%;
            --sidebar-primary-foreground: 210 40% 98%;
            --accent: 216 34% 17%;
            --accent-foreground: 210 40% 98%;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="font-sans bg-background text-foreground">
    <div class="flex h-screen bg-background">
        @include('components.sidebar')
        
        <div class="flex-1 flex flex-col overflow-hidden">
            @yield('content')
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Toast notifications
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
</body>
</html>
