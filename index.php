<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="body-content flex flex-column">
        <main class="flex flex-column gap-3">
            <section class="flex gap-2 items-center justify-between">
                <div id="bulkActions" class="bulk-actions hidden items-center">
                    <i class="icon delete-all-record">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                        </svg>
                    </i>

                    <small id="labelItemsSelected">0 items selected</small>

                    <i class="icon unchecked-all-record">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
                            <path d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z"></path>
                        </svg>
                    </i>
                </div>

                <h1 id="title" class="leading-none ">Add Product</h1>

                <div class="flex gap-1 items-center">
                    <button class="button icon add-product">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#fdfdfe" viewBox="0 0 256 256">
                            <path d="M228,128a12,12,0,0,1-12,12H140v76a12,12,0,0,1-24,0V140H40a12,12,0,0,1,0-24h76V40a12,12,0,0,1,24,0v76h76A12,12,0,0,1,228,128Z"></path>
                        </svg>
                    </button>
                </div>
            </section>

            <section class="flex flex-column gap-2">
                <table class="product-list-table">
                    <tr class="no-hover">
                        <th></th>
                        <th><span>Product Name</span></th>
                        <th><span>Price</span></th>
                        <th><span>Qty</span></th>
                        <th><span>Discount</span></th>
                        <th><span>Total</span></th>
                    </tr>
                   <tr>
                        <td><input class="input checkbox" type="checkbox"/></td>
                        <td><input type="text" name="product_name"/></td>
                        <td><input type="text" name="price" onkeyup="this.value = this.value.replace(/[^\d]/g, '')" /></td>
                        <td><input type="text" name="qty" onkeyup="this.value = this.value.replace(/[^\d]/g, '')" /></td>
                        <td><input type="text" name="discount" onkeyup="this.value = this.value.replace(/[^\d]/g, '')" /></td>
                        <td class="total"></td>
                    </tr>
                </table>
                <div class="total flex">
                    Grand Total : <span class="grand-total-amount">0</span>
                </div>
            </section>
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            // Function to calculate row total
            function calculateRowTotal(row) {
                var price = parseFloat(row.find('input[name="price"]').val()) || 0;
                var qty = parseInt(row.find('input[name="qty"]').val()) || 0;
                var discount = parseFloat(row.find('input[name="discount"]').val()) || 0;
                var total = (price * qty) - discount;
                row.find('.total').text(total.toFixed(2));
                return total;
            }

            // Function to calculate grand total
            function calculateGrandTotal() {
                var grandTotal = 0;
                $('.product-list-table tbody tr').each(function(){
                    grandTotal += calculateRowTotal($(this));
                });
                $('.grand-total-amount').text(grandTotal.toFixed(2));
            }

            // Add new product column
            $('.add-product').click(function(){
                var newRow = $('.product-list-table tr:last').clone();
                newRow.find('input[type="text"]').val('');
                newRow.find('input[type="checkbox"]').prop('checked', false);
                $('.product-list-table').append(newRow);
                // Calculate grand total after adding the new row
                calculateGrandTotal();
            });

            // Calculate totals when inputs change
            $('.product-list-table').on('input', 'input[type="text"]', function(){
                calculateRowTotal($(this).closest('tr'));
                calculateGrandTotal();
            });
            // checked checkbox toggle and count 
            $('.product-list-table').on('click', 'input.checkbox', function(){
                var checkedCount = $('.product-list-table tr input.checkbox:checked').length;
                console.log(checkedCount)
                $('#labelItemsSelected').html(checkedCount+ " items selected")
                if (checkedCount === 0) {
                    $('.leading-none').removeClass('hidden');
                } else {
                    $('.leading-none').addClass('hidden');
                }
                // Toggle the visibility of bulk actions based on the number of checkboxes checked
                if (checkedCount > 0) {
                    $('.bulk-actions').removeClass('hidden');
                } else {
                    $('.bulk-actions').addClass('hidden');
                }
            });
            //Delete checked row 
            $('.delete-all-record').click(function(){
                var checkedCheckboxes = $('.product-list-table input.checkbox:checked');
                if (checkedCheckboxes.length > 0) {
                    var newRow = $('.product-list-table tr:last').clone();
                    newRow.find('input[type="text"]').val('');
                    newRow.find('input[type="checkbox"]').prop('checked', false);
                    checkedCheckboxes.each(function(){
                        var row = $(this).closest('tr');
                        row.remove();
                    });
                    // Check if the last row is the only row left
                    if ($('.product-list-table tr').length === 1) {
                        $('.product-list-table tbody').append(newRow);
                    }
                    $('.leading-none').removeClass('hidden');
                    $('.bulk-actions').addClass('hidden');
                    calculateGrandTotal();
                }
            });
            // Deselect all checkbox 
            $('.unchecked-all-record').click(function(){
                var checkedCheckboxes = $('.product-list-table input.checkbox:checked');
                checkedCheckboxes.each(function(){
                    $(this).prop('checked', false);
                    $('.leading-none').removeClass('hidden');
                    $('.bulk-actions').addClass('hidden');
                });   
            });
        });
    </script>
</body>

</html>
