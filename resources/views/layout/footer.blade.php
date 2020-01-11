<!-- Footer -->
<footer class="footer text-center">
  <div class="container">

    <p class="text-muted small mb-0">Copyright &copy; Cost My Travel {{ date('Y') }}</p>
  </div>
</footer>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded js-scroll-trigger" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Bootstrap core JavaScript -->
<script src="{{ asset("/jquery/jquery.min.js") }}"></script>
<script src="{{ asset("/bootstrap/js/bootstrap.min.js") }}"></script>

<!-- Plugin JavaScript -->
<script src="{{ asset("/jquery-easing/jquery.easing.min.js") }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.2.2/dist/latest/bootstrap-autocomplete.min.js"></script>


<!-- Custom scripts for this template -->
<script src="{{ asset("/js/stylish-portfolio.min.js") }}"></script>

@stack('scripts')
