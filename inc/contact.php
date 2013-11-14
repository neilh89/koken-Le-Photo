<koken:head>
    <title>Kontakt - LePhoto.se</title>
</koken:head>

<koken:load>
    <div id="text">
        <article>
            <header>
                <h2>Kontakt</h2>
            </header>
            <p id="headline"></p>

            <form action="/storage/themes/LePhoto/helpers/webmailer.php" id="form" method="post" class="contactform">
                <p class="input">
                    <label for="contactName">Ditt namn:</label>
                    <input type="text" name="name" id="name" style="width: 99%; font-size: 0.95em;"/>
                </p>

                <p class="input">
                    <label for="email">Din epost:</label>
                    <input type="email" name="email" id="email" style="width: 99%; font-size: 0.95em;"/>
                </p>

                <img id="captchaImage" src="/storage/themes/LePhoto/helpers/captcha.php" alt="captcha" style="position:relative; float:left; margin-right:10px;"/>

                <p class="input">
                    <label for="captcha">Bekräfta koden till vänster:</label><br />
                    <input type="text" name="captcha" id="captcha" style="width: 7ex; font-size: 0.95em;"/>
                </p>

		<hr style="width:100%;clear:both;visibility:hidden;">

                <p class="textarea">
                    <label for="message">Meddelande:</label>
                    <textarea name="message" id="message" rows="5" style="width: 99%; font-size: 0.95em;"></textarea>
                </p>

                <p class="button">
                    <button type="submit" id="submitButton">Skicka</button>
                </p>

                <input type="hidden" name="operation" id="operation" value="submit"/>
            </form>
            </article>
    </div>
    <script>
        jQuery('#captchaImage').bind('load', function () {
            // gets the validation of a former request of webmailer.php
            $.ajax({
                type: "POST",
                url: "/storage/themes/LePhoto/helpers/webmailer.php",
                data: {"operation": "notification"},
                success: function (data) {
                    switch (data.status) {
                        case "new":
                        {
                            // hides the status div - no former request
                            $('#headline').css('display', 'none');
                            break;
                        }
                        case "error":
                        {
                            // error during last request
                            $('#headline').html(data.notification);
                            $('#headline').addClass('error');
                            $.each(data.formData, function (key, formField) {
                                if (formField.data) {
                                    $('#' + key).val(formField.data);
                                }
                                if (formField.validation) {
                                    $('#' + key).css('background-color', 'rgb(255, 224, 224)');
                                    $('#' + key).attr('placeholder', formField.validation);
                                }
                            });

                            break;
                        }
                        case "success":
                        {
                            // no error
                            $('#headline').html(data.notification);
                            $('#headline').addClass('success');
                            break;
                        }
                        default:
                            return;
                    }
                },
                dataType: "json"
            });
        });
    </script>
</koken:load>
