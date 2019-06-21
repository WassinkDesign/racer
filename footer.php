</div>

</main>
<footer class="page-footer green darken-1">
<!-- <div class="container">
    <div class="row">
        <div class="col l6 s12">
            <h5 class="white-text">Racer Info</h5>
            <p class="grey-text text-lighten-4">This app is about all these awesome things.</p>
        </div>
        <div class="col l3 s12">
            <h5>Sponsors</h5>
            <ul>
                <li><a class="white-text" href="#!">Sponsor 1</a></li>
            </ul>
        </div>
    </div>
</div> -->
<div class="footer-copyright">
    <div class="container">
        Made by <a href="https://www.linkedin.com/in/angelawassink/" target="_blank" class="white-text">Angie Wassink</a>
    </div>
</div>

</footer>
  <!--  Scripts-->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/2.14.1/moment.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

    <script src="<?php echo url_for('js/materialize.js'); ?>"></script>
    <script src="<?php echo url_for('js/init.js'); ?>"></script>
    <script type="text/javascript">
        $(function () {
            $('.showDate').datetimepicker({
                inline: false,
                sideBySide: true,
                format: 'MMM DD, YYYY'
            });
        });
        $(function () {
            $('.showTime').datetimepicker({
                inline: false,
                format: 'LT'
            });
        });
    </script>
</body>
  </html>
