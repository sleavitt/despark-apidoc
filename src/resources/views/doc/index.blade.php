<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="icon" type="image/png" href="{{env('APP_URL')}}/apidoc/swagger/images/favicon-32x32.png" sizes="32x32"/>
    <link rel="icon" type="image/png" href="{{env('APP_URL')}}/apidoc/swagger/images/favicon-16x16.png" sizes="16x16"/>
    <link href='{{env('APP_URL')}}/apidoc/swagger/css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='{{env('APP_URL')}}/apidoc/swagger/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='{{env('APP_URL')}}/apidoc/swagger/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='{{env('APP_URL')}}/apidoc/swagger/css/reset.css' media='print' rel='stylesheet' type='text/css'/>
    <link href='{{env('APP_URL')}}/apidoc/swagger/css/print.css' media='print' rel='stylesheet' type='text/css'/>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/jquery.slideto.min.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/jquery.wiggle.min.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/handlebars-2.0.0.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/underscore-min.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/backbone-min.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/swagger-ui.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/highlight.7.3.pack.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/jsoneditor.min.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/marked.js' type='text/javascript'></script>
    <script src='{{env('APP_URL')}}/apidoc/swagger/lib/swagger-oauth.js' type='text/javascript'></script>

    <!-- Some basic translations -->
    <!-- <script src='lang/translator.js' type='text/javascript'></script> -->
    <!-- <script src='lang/ru.js' type='text/javascript'></script> -->
    <!-- <script src='lang/en.js' type='text/javascript'></script> -->

    <script type="text/javascript">
        $(function () {
            var url = window.location.search.match(/url=([^&]+)/);
            if (url && url.length > 1) {
                url = decodeURIComponent(url[1]);
            } else {
                url = "{{env('APP_URL')}}/api-doc/json";
            }

            // Pre load translate...
            if (window.SwaggerTranslator) {
                window.SwaggerTranslator.translate();
            }
            window.swaggerUi = new SwaggerUi({
                url: url,
                dom_id: "swagger-ui-container",
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                onComplete: function (swaggerApi, swaggerUi) {
                    if (typeof initOAuth == "function") {
                        initOAuth({
                            clientId: "your-client-id",
                            clientSecret: "your-client-secret-if-required",
                            realm: "your-realms",
                            appName: "your-app-name",
                            scopeSeparator: ",",
                            additionalQueryStringParams: {}
                        });
                    }

                    if (window.SwaggerTranslator) {
                        window.SwaggerTranslator.translate();
                    }

                    $('pre code').each(function (i, e) {
                        hljs.highlightBlock(e)
                    });

                    addApiKeyAuthorization();
                },
                onFailure: function (data) {
                    log("Unable to Load SwaggerUI");
                },
                docExpansion: "none",
                jsonEditor: false,
                apisSorter: "alpha",
                defaultModelRendering: 'schema',
                showRequestHeaders: false
            });

            @if(config('apidoc.authorization') == 'jwt')

            function addApiKeyAuthorization() {
                var key = $('#input_apiKey')[0].value;
                key = 'Bearer ' + key;
                if (key && key.trim() != "") {
                    var apiKeyAuth = new SwaggerClient.ApiKeyAuthorization("Authorization", key, "header");
                    window.swaggerUi.api.clientAuthorizations.add("api_key", apiKeyAuth);
                    log("added key " + key);
                }
            }

            @else
            function addApiKeyAuthorization() {
                var key = encodeURIComponent($('#input_apiKey')[0].value);
                if (key && key.trim() != "") {
                    var apiKeyAuth = new SwaggerClient.ApiKeyAuthorization("api_key", key, "query");
                    window.swaggerUi.api.clientAuthorizations.add("api_key", apiKeyAuth);
                    log("added key " + key);
                }
            }

            @endif
                        $('#input_apiKey').change(addApiKeyAuthorization);

            // if you have an apiKey you would like to pre-populate on the page for demonstration purposes...
            /*
             var apiKey = "myApiKeyXXXX123456789";
             $('#input_apiKey').val(apiKey);
             */

            window.swaggerUi.load();

            function log() {
                if ('console' in window) {
                    console.log.apply(console, arguments);
                }
            }
        });
    </script>
</head>

<body class="swagger-section">
<div id='header'>
    <div class="swagger-ui-wrap">
        <a id="logo" href="http://swagger.io">swagger</a>

        <form id='api_selector'>
            <div class='input'><input placeholder="http://example.com/api" id="input_baseUrl" name="baseUrl"
                                      type="text"/></div>
            <div class='input'><input placeholder="api_key" id="input_apiKey" name="apiKey" type="text"/></div>
            <div class='input'><a id="explore" href="#" data-sw-translate>Explore</a></div>
        </form>
    </div>
</div>

<div id="message-bar" class="swagger-ui-wrap" data-sw-translate>&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
