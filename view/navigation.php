<?php
function createPagination(
    int $totalRecords,
    int $recordsPerPage,
    int $currentPage,
    string $baseUrl,
    int $maxLinks = 10
) {

    $totalPages = (int)ceil($totalRecords / $recordsPerPage);

    $html = '<nav aria-label="Page navigation">';
    $html .=  '<ul class="pagination mt-2 justify-content-center">';
    $disabled = $currentPage === 1 ? ' disabled' : '';
    $previous = max(($currentPage - 1), 1);
    $html .=  '<li class="page-item' . $disabled . '">
            <a  href="' . $baseUrl . ' &page=' . $previous . '" class="page-link">Previous</a>
        </li>';

    // current page 5,6,7,8,9  10, 11,12,13,14,15,16,17
       $startPage =(int) max(1,$currentPage - floor($maxLinks / 2));
       $endPage = min($startPage + $maxLinks - 1, $totalPages);

       if(($endPage - $startPage +1) < $maxLinks){
           $startPage = max(1, $endPage - $maxLinks + 1 );
       }

    for ($i = $startPage; $i <= $endPage; $i++) {
        
        if($i === $currentPage){
             $html .= '<li class="page-item"><span class="page-link active">'.$i.'</span></li>';
        } else{
            $html .=  '<li class="page-item"><a class="page-link" href="' . $baseUrl . '&page=' . $i . '">' . $i . '</a></li>';

        }
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
