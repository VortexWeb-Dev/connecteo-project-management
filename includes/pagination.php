<div class="pagination mt-4">
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="flex justify-center space-x-2">
                <!-- Previous Button -->
                <li class="<?= $page <= 1 ? 'opacity-50 pointer-events-none' : '' ?>">
                    <a class="block px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100" href="?page=<?= max(1, $page - 1) ?>" aria-label="Previous">
                        &laquo;
                    </a>
                </li>

                <!-- Page Numbers -->
                <?php
                $range = 2;
                $showPages = [1, $totalPages];

                for ($i = max(2, $page - $range); $i <= min($totalPages - 1, $page + $range); $i++) {
                    $showPages[] = $i;
                }

                $showPages = array_unique(array_merge($showPages, range(1, min(3, $totalPages)), range(max(1, $totalPages - 2), $totalPages)));

                for ($i = 1; $i <= $totalPages; $i++):
                    if (in_array($i, $showPages)):
                ?>
                    <li>
                        <a class="block px-3 py-1 <?= $i === $page ? 'bg-blue-500 text-white' : 'text-gray-700 bg-white hover:bg-gray-100' ?> border border-gray-300 rounded-md" href="?page=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php elseif ($i < $page && !in_array($i + 1, $showPages)): ?>
                        <li><span class="block px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md">...</span></li>
                        <?php $i = $page - $range - 1;
                        ?>
                    <?php elseif ($i > $page && !in_array($i - 1, $showPages)): ?>
                        <li><span class="block px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md">...</span></li>
                        <?php $i = $totalPages - 2;
                        ?>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Next Button -->
                <li class="<?= $page >= $totalPages ? 'opacity-50 pointer-events-none' : '' ?>">
                    <a class="block px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100" href="?page=<?= min($totalPages, $page + 1) ?>" aria-label="Next">
                        &raquo;
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>