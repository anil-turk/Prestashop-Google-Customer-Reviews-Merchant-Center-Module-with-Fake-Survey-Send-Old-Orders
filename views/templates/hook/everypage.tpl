

{addJsDef GCREVIEW_ID=$GCREVIEW_ID|escape:'html':'UTF-8'}
{addJsDef GCREVIEW_STYLE=$GCREVIEW_STYLE|escape:'html':'UTF-8'}
{addJsDef GCREVIEW_BADGE_STYLE=$GCREVIEW_BADGE_STYLE|escape:'html':'UTF-8'}


{if $GCREVIEW_BADGE_STYLE!='none'}

    {literal}
        <!-- BEGIN GCR Badge Code -->
        <script src="https://apis.google.com/js/platform.js?onload=renderBadge"
                data-keepinline="true" async defer>
        </script>
        <script data-keepinline="true">
        window.renderBadge = function() {
            var ratingBadgeContainer = document.createElement("div");
            document.body.appendChild(ratingBadgeContainer);
            window.gapi.load('ratingbadge', function() {
                window.gapi.ratingbadge.render(
                    ratingBadgeContainer, {
                        // REQUIRED
                        "merchant_id": {/literal}{$GCREVIEW_ID|intval}{literal},
                        // OPTIONAL
                        "position": "{/literal}{$GCREVIEW_BADGE_STYLE|escape:'htmlall':'UTF-8'}{literal}"
                    });
            });
        }
    </script>
    <!-- END GCR Badge Code -->

{/literal}
{/if}