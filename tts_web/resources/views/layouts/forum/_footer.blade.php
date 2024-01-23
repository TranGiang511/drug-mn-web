<!-- footer -->
<footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

        <div class="col-lg-4">
            <div class="footer-info" data-aos="fade-up" data-aos-delay="50">
              <h3>VINICORP</h3>
              <p class="pb-3"><em>Website TTS</em></p>
              <p>
                Số 123  <br>
                Hà Nội<br><br>
                <strong>Phone:</strong> +8445555553<br>
                <strong>Email:</strong> vinicorp@gmail.com<br>
              </p>
              <div class="social-links mt-3">
                <a href="#" class="twitter"><i class="fa fa-twitter"></i></i></a>
                <a href="#" class="facebook"><i class="fa fa-facebook-f"></i></a>
                <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
                <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
                <a href="#" class="linkedin"><i class="fa fa-linkedin-square"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="150">
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#about">Tag1</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#services">Tag2</a></li>
            </ul>
          </div>
          <div class="col-lg-4 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="150" >
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#testimonials">Tag3</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#team">Tag4</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#contact">Tag5</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Vinicorp</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Designed by <a href="{{ url("/home")}}">GiangTran</a>
      </div>
    </div>
  </footer>
  <!-- End Footer -->

<!-- Svg navbar -->
<svg width="0" height="0" class="hidden">
  <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 73 63" id="icon-menu_icon"><path d="M68.5 9h-64a4.5 4.5 0 0 1 0-9h64a4.5 4.5 0 0 1 0 9zm0 27h-64a4.5 4.5 0 0 1 0-9h64c2.49 0 4.5 2.01 4.5 4.5S70.99 36 68.5 36zm0 27h-64a4.5 4.5 0 0 1 0-9h64c2.49 0 4.5 2.01 4.5 4.5S70.99 63 68.5 63z"></path></symbol>
  <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 81 81" id="icon-cancel"><path d="M76.5 81c-1.15 0-2.3-.44-3.18-1.32l-72-72a4.49 4.49 0 0 1 0-6.36 4.49 4.49 0 0 1 6.36 0l72 72a4.49 4.49 0 0 1 0 6.36c-.88.88-2.03 1.32-3.18 1.32z"></path><path d="M4.5 81c-1.15 0-2.3-.44-3.18-1.32a4.49 4.49 0 0 1 0-6.36l72-72a4.49 4.49 0 0 1 6.36 0 4.49 4.49 0 0 1 0 6.36l-72 72C6.8 80.56 5.65 81 4.5 81z"></path></symbol>
</svg>

<!-- ckeditor -->
<script src="{{ asset('plugin/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('plugin/ckfinder/ckfinder.js') }}"></script>
<script>
    createCkeditor('content');
    function createCkeditor(name) {
          CKEDITOR.replace(name, {
              filebrowserBrowseUrl: "{{ asset('plugin/ckfinder/ckfinder.html') }}",
              filebrowserImageBrowseUrl: "{{ asset('plugin/ckfinder/ckfinder.html?type=Images') }}",
              filebrowserFlashBrowseUrl: "{{ asset('plugin/ckfinder/ckfinder.html?type=Flash') }}",
              filebrowserUploadUrl: "{{ asset('plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",
              filebrowserImageUploadUrl: "{{ asset('plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}",
              filebrowserFlashUploadUrl: "{{ asset('plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') }}",
          });
      }
</script>

<!-- bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>

<!-- JS custom -->
<script src="{{ asset('js/create-news.js') }}"></script>
<script src="{{ asset('js/form-news-edit.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/forum.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<!-- Navbar mobile -->
<script src="{{ asset('js/bundle.js') }}"></script>

<!-- Get selected language -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
      const languageDropdown = document.getElementById('languageDropdown');
      const languageLinks = document.querySelectorAll('.dropdown-menu .dropdown-item');

      languageLinks.forEach(link => {
          link.addEventListener('click', function(event) {
              event.preventDefault();

              const selectedLanguage = this.textContent;
              const selectedLanguageValue = this.getAttribute('href');

              languageDropdown.textContent = selectedLanguage;
              languageDropdown.setAttribute('href', selectedLanguageValue);

              window.location.href = selectedLanguageValue;
          });
      });
  });
</script>
</body>
</html>