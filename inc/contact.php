<koken:head>
    <title>Contact - domain.tld</title>
</koken:head>

<koken:load>
    <div id="text">
        <article>
            <header>
                <time>July 2, 2013</time>
                <h2>Contact me</h2>
            </header>
            <p id="headline"></p>

            <form action="../helpers/webmailer.php" id="form" method="post" class="contactform">
                <p class="input">
                    <label for="contactName">Your name:</label>
                    <input type="text" name="name" id="name" style="width: 99%; font-size: 0.95em;"/>
                </p>

                <p class="input">
                    <label for="email">Your e-mail:</label>
                    <input type="email" name="email" id="email" style="width: 99%; font-size: 0.95em;"/>
                </p>

                <img id="captchaImage" src="../helpers/captcha.php" alt="captcha" style="margin-top: -1em;"/>

                <p class="input">
                    <label for="captcha">Verification code:</label>
                    <input type="text" name="captcha" id="captcha" style="width: 99%; font-size: 0.95em;"/>
                </p>

                <p class="textarea">
                    <label for="message">Your message:</label>
                    <textarea name="message" id="message" rows="5" style="width: 99%; font-size: 0.95em;"></textarea>
                </p>

                <p class="button">
                    <button type="submit" id="submitButton">Send message</button>
                </p>

                <input type="hidden" name="operation" id="operation" value="submit"/>
            </form>
            </article>
    </div>
    <script>
        jQuery('#captchaImage').bind('load', function () {
            $('nav>ul').each(
                // sets the correct sub page as selected in header and footer
                function (index, value) {
                    $(value).children().last().children().first().addClass('k-nav-current');
                }
            );
            // gets the validation of a former request of webmailer.php
            $.ajax({
                type: "POST",
                url: "../helpers/webmailer.php",
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
