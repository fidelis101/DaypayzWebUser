</div>
	
	<script src="js/popper.js/dist/umd/popper.min.js"></script>
	<script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
	             
    <script src="js/jquery/dist/jquery.min.js"></script>
    <script src="js/popper.js/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

	<script src="js/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="js/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="js/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="js/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
                 
    <script src="js/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="js/widgets.js"></script>
    <script src="js/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="js/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <script src="js/jqvmap/dist/maps/jquery.vmap.world.js"></script>
	             
    <script src="js/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="js/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="js/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="js/init-scripts/data-table/datatables-init.js"></script>
	
	<script src="js/jquery/dist/jquery.min.js"></script>
	
	<script src="js/main.js"></script>
	<script src="js/chosen/chosen.jquery.min.js"></script>

	<script>
		jQuery(document).ready(function() {
			jQuery(".standardSelect").chosen({
				disable_search_threshold: 10,
				no_results_text: "Oops, nothing found!",
				width: "100%"
			});
		});
	</script>
    <script>
        (function($) {
            "use strict";

            jQuery('#vmap').vectorMap({
                map: 'world_en',
                backgroundColor: null,
                color: '#ffffff',
                hoverOpacity: 0.7,
                selectedColor: '#1de9b6',
                enableZoom: true,
                showTooltip: true,
                values: sample_data,
                scaleColors: ['#1de9b6', '#03a9f5'],
                normalizeFunction: 'polynomial'
            });
        })(jQuery);
        
        
    </script>
	 <!--Contactform script -->
  <script src="contactform/contactform.js"></script>
<script language="JavaScript" type="text/javascript">
TrustLogo("https://www.daypayz.com/img/sectigo_trust_seal_sm_82x32.png", "CL1", "none");
</script>
<a  href="https://ssl.comodo.com" id="comodoTL">Comodo SSL</a>
</body>

</html>
