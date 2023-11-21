<div class="row payment-plugin" id="cashPayment" style="display: none;">
    <div class="col-md-8 col-sm-12 box-center center mt-4 mb-0">
        <div class="row">
            
            <div class="col-xl-12 text-center">
              {{ trans('cash::messages.Payment with Cash/Cheque') }}
            </div>
            
            <!-- ... -->
        
        </div>
    </div>
</div>

@section('after_scripts')
    @parent
    <script>
        $(document).ready(function ()
        {
            var selectedPackage = $('input[name=package_id]:checked').val();
            var packagePrice = getPackagePrice(selectedPackage);
            var paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
    
            /* Check Payment Method */
            checkPaymentMethodForCash(paymentMethod, packagePrice);
            
            $('#paymentMethodId').on('change', function () {
                paymentMethod = $(this).find('option:selected').data('name');
                checkPaymentMethodForCash(paymentMethod, packagePrice);
            });
            $('.package-selection').on('click', function () {
                selectedPackage = $(this).val();
                packagePrice = getPackagePrice(selectedPackage);
                paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
                checkPaymentMethodForCash(paymentMethod, packagePrice);
            });
    
            /* Send Payment Request */
            $('#submitPostForm').on('click', function (e)
            {
                e.preventDefault();
        
                paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
                
                if (paymentMethod != 'cash' || packagePrice <= 0) {
                    return false;
                }
    
                $('#postForm').submit();
        
                /* Prevent form from submitting */
                return false;
            });
        });

        function checkPaymentMethodForCash(paymentMethod, packagePrice)
        {
            if (paymentMethod == 'cash' && packagePrice > 0) {
                $('#cashPayment').show();
            } else {
                $('#cashPayment').hide();
            }
        }
    </script>
@endsection
