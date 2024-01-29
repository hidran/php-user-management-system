<?php
function createPagination(
    int $totalRecords,
    int $recordsPerPage,
    int $currentPage,
    string $baseUrl
) {

    $totalPages = (int)ceil($totalRecords / $recordsPerPage);

    $html = '<nav aria-label="Page navigation">';
    $html .=  '<ul class="pagination mt-2 justify-content-center">';
    $disabled = $currentPage === 1 ? ' disabled' : '';
    $previous = max(($currentPage - 1), 1);
    $html .=  '<li class="page-item' . $disabled . '">
            <a  href="' . $baseUrl . ' &page=' . $previous . '" class="page-link">Previous</a>
        </li>';
    for ($i = 1; $i <= $totalPages; $i++) {
        $activeClass = $i == $currentPage ? ' active' : '';
        $disabled = $i == $currentPage ? ' disabled' : '';
        $html .=  '<li class="page-item"><a class="page-link' . $activeClass . $disabled . '" href="' . $baseUrl . '&page=' . $i . '">' . $i . '</a></li>';
    }


    $disabled = $currentPage === $totalPages ? ' disabled' : '';

    $next = min(($currentPage + 1), $totalPages);
    $html .=
        '<li class="page-item' . $disabled . '">
            <a  href="' . $baseUrl . ' &page=' . $next . '" class="page-link' . $disabled . '">Next </a>
        </li>';
    $html .= '</ul>
</nav>';
    return $html;
}
