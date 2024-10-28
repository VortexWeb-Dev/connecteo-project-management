<nav class="bg-gray-800 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">

        <!-- Logo or Brand Name -->
        <div class="text-2xl font-bold">
            <a href="index.php" class="hover:text-gray-300">Connecteo</a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden md:flex space-x-4">
            <a href="dashboard.php" class="hover:text-gray-300 <?php echo $current_page === 'dashboard.php' ? 'text-gray-300' : ''; ?>">Dashboard</a>
        </div>
    </div>
</nav>