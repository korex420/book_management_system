$(document).ready(function() {
    // Auto-complete for author field
    $('#author').on('input', function() {
        const query = $(this).val();
        if (query.length < 2) return;
        
        $.ajax({
            url: 'ajax_autocomplete.php',
            method: 'POST',
            data: {
                type: 'author',
                query: query
            },
            success: function(response) {
                try {
                    const authors = JSON.parse(response);
                    showAutocompleteSuggestions(authors, '#author');
                } catch (e) {
                    console.error('Error parsing autocomplete response:', e);
                }
            },
            error: function(xhr, status, error) {
                console.error('Autocomplete error:', error);
            }
        });
    });
    
    // Auto-complete for genre field
    $('#genre').on('input', function() {
        const query = $(this).val();
        if (query.length < 1) return;
        
        $.ajax({
            url: 'ajax_autocomplete.php',
            method: 'POST',
            data: {
                type: 'genre',
                query: query
            },
            success: function(response) {
                try {
                    const genres = JSON.parse(response);
                    showAutocompleteSuggestions(genres, '#genre');
                } catch (e) {
                    console.error('Error parsing autocomplete response:', e);
                }
            }
        });
    });
    
    // Form validation enhancement
    $('#bookForm').on('submit', function(e) {
        let isValid = true;
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Validate required fields
        $('#bookForm [required]').each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
                $(this).addClass('is-invalid');
                $(this).after('<div class="invalid-feedback">This field is required</div>');
            }
        });
        
        // Validate year range
        const year = $('#publication_year').val();
        const currentYear = new Date().getFullYear();
        if (year && (year < 1900 || year > currentYear)) {
            isValid = false;
            $('#publication_year').addClass('is-invalid');
            $('#publication_year').after(`<div class="invalid-feedback">Year must be between 1900 and ${currentYear}</div>`);
        }
        
        // Validate price
        const price = $('#price').val();
        if (price && price < 0) {
            isValid = false;
            $('#price').addClass('is-invalid');
            $('#price').after('<div class="invalid-feedback">Price cannot be negative</div>');
        }
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            $('.is-invalid').first().focus();
        }
    });
    
    // Price range validation in search
    $('#min_price, #max_price').on('change', function() {
        const minPrice = parseFloat($('#min_price').val()) || 0;
        const maxPrice = parseFloat($('#max_price').val()) || Infinity;
        
        if (minPrice > maxPrice) {
            $('#max_price').addClass('is-invalid');
            $('#max_price').after('<div class="invalid-feedback">Max price cannot be less than min price</div>');
        } else {
            $('#max_price').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }
    });
    
    // Year range validation in search
    $('#min_year, #max_year').on('change', function() {
        const minYear = parseInt($('#min_year').val()) || 0;
        const maxYear = parseInt($('#max_year').val()) || Infinity;
        
        if (minYear > maxYear) {
            $('#max_year').addClass('is-invalid');
            $('#max_year').after('<div class="invalid-feedback">Max year cannot be less than min year</div>');
        } else {
            $('#max_year').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }
    });
    
    // Quick search examples
    $('.card a').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        window.location.href = href;
    });
});

// Show autocomplete suggestions
function showAutocompleteSuggestions(suggestions, target) {
    // Remove existing suggestions
    $(target).siblings('.autocomplete-suggestions').remove();
    
    if (suggestions.length > 0) {
        const suggestionsHtml = suggestions.map(suggestion => 
            `<div class="autocomplete-suggestion">${suggestion}</div>`
        ).join('');
        
        const suggestionsDiv = $(`<div class="autocomplete-suggestions">${suggestionsHtml}</div>`);
        $(target).after(suggestionsDiv);
        
        // Handle suggestion click
        suggestionsDiv.on('click', '.autocomplete-suggestion', function() {
            $(target).val($(this).text());
            suggestionsDiv.remove();
        });
        
        // Remove suggestions when clicking elsewhere
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.autocomplete-suggestions').length && !$(e.target).is(target)) {
                suggestionsDiv.remove();
            }
        });
    }
}

// Live search for main page
let searchTimeout;
$('#searchInput').on('input', function() {
    clearTimeout(searchTimeout);
    const query = $(this).val();
    
    searchTimeout = setTimeout(() => {
        if (query.length >= 2 || query.length === 0) {
            performLiveSearch(query);
        }
    }, 300);
});

function performLiveSearch(query) {
    $.ajax({
        url: 'ajax_search.php',
        method: 'GET',
        data: { query: query },
        beforeSend: function() {
            $('#searchResults').html('<div class="text-center"><div class="loading"></div> Searching...</div>');
        },
        success: function(response) {
            $('#searchResults').html(response);
        },
        error: function() {
            $('#searchResults').html('<div class="alert alert-danger">Search failed. Please try again.</div>');
        }
    });
}