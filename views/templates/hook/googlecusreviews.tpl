
{addJsDef GCREVIEW_ID=$GCREVIEW_ID|escape:'html':'UTF-8'}
{addJsDef GCREVIEW_STYLE=$GCREVIEW_STYLE|escape:'html':'UTF-8'}
{addJsDef GCREVIEW_BADGE_STYLE=$GCREVIEW_BADGE_STYLE|escape:'html':'UTF-8'}

{if $GCREVIEW_STYLE!='none'}
{literal}

<!-- BEGIN GCR Opt-in Module Code -->
    <script src="https://apis.google.com/js/platform.js?onload=renderOptIn"
            data-keepinline="true"   async defer>
    </script>

    <script data-keepinline="true">
        window.renderOptIn = function() {
            window.gapi.load('surveyoptin', function() {
                window.gapi.surveyoptin.render(
                    {
                        // REQUIRED
                        "merchant_id":"{/literal}{$GCREVIEW_ID|intval}{literal}",
                        "order_id": "{/literal}{$ORDER_ID|intval}{literal}",
                        "email": "{/literal}{$CUSTOMER_MAIL|escape:'htmlall':'UTF-8'}{literal}",
                        "delivery_country": "{/literal}{$LANG|escape:'htmlall':'UTF-8'}{literal}",
                        "estimated_delivery_date": "{/literal}{$ORDER_DATE|escape:'htmlall':'UTF-8'}{literal}",

                        // OPTIONAL
                        "opt_in_style": "{/literal}{$GCREVIEW_STYLE|escape:'htmlall':'UTF-8'}{literal}"
                    });
            });
        }
    </script>
<!-- END GCR Opt-in Module Code -->
{/literal}
{/if}
