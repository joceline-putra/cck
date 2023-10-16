$(document).ready(function(){

  // checkDatabase();
  // dataJson();
});

// $("#BtnAddProduct").click(function(){
// })
  
  // $("#fkategori").change(function(){
  // });

// window.setTimeout(function(){ 
// },1500);  
// function gotoHome(){ window.location.href = "www.rumahpapua.com"; }
    


        function readURL1(input) {
          if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah1')
                        .attr('src', e.target.result)
                        .width(70)
                        .height(70); 
                };
                reader.readAsDataURL(input.files[0]);
                // <input type='file' onchange="readURL1(this);" name="image_file[]"/>
                // <img id="blah1" src="assets/images/preview2.png" alt="your image" width="70px" height="70px"/>        
          }
        } 

        function loadKontak(){
            $('#table').DataTable( {
                "destroy": true,
                "ajax": "pages/ajax/contact-data.php",
                "deferRender": true
            } );
        }

        function loadReport(){
            $('#result').load("pages/ajax/report.php");
        }

        function emptyCashOut(){
            $('#var1').val('');
            $('#var2').val('');
            // $('#').val('');               
        } 



//  Check Data 
    function checkPing(){ 
      $.ajax({ 
         url: "services/?act=check-ping", 
         cache: false, 
         success: function(data){ 
             $("#result").html(data); 
         },
         error : function(data){} 
      }); 
      var waktu = setTimeout("checkPing()",10000); 
    }  	

    function checkDatabase(){ 
      $.ajax({ 
         url: "services/?act=check-database&a=db_kaylajewelry", 
         cache: false, 
         success: function(data){ 
             $("#result").html(data); 
         },
         error : function(data){} 
      }); 
      var waktu = setTimeout("checkDatabase()",10000); 
    }       


// Data CRUD
    function dataSave(){
      // $("#frm-edit-product").on("submit", function(event) {
      // event.preventDefault();
      $.ajax({
          type: "POST",        
          url: "services/?act=data-save",
          // data: $("#formPermintaan").serialize(),
          beforeSend:function(){
          },
          success:function(data){

          },
          error:function(xhr, Status, err){
              alert('Gagal');
          }
      });
      return false;
    }

    function dataView(a,b){
      $.ajax({
          type: "GET",
          url: "services/",
          data: "act=data-view&a="+a+"&b="+b,
          success: function(data){
            alert(data);
            // var obj = JSON.parse(result);
            //   var idPP = obj['kdproduct'];
            //   $('#imagePP').attr('src',imagePP);
          },
          error: function(data){

          }
      });
    }  
    function dataUpdate(a){
      $.ajax({
          type: "POST",        
          url: "services/?act=data-update",
          // data: $("#formPermintaan").serialize(),
          beforeSend:function(){
          },
          success:function(data){
            alert(data);
          },
          error:function(xhr, Status, err){
              alert('Gagal');
          }
      });
      return false;
    }

    function dataDelete(a){
      $.ajax({
          type: "GET",
          url: "services/",
          data: "act=data-delete&a="+a,
          success: function(data){
            alert(data);
            // var obj = JSON.parse(result);
            //   var idPP = obj['kdproduct'];
            //   $('#imagePP').attr('src',imagePP);
          },
          error: function(data){

          }
      });
    }  

    function dataJson(a){
      var data;
      $.ajax({
          dataType: "json",        
          url: "coba.json",
          data: data,
          success: function(data){
            // console.log(data[0].id);
          }
      });
    }   

    function generateJson(tablename){
      // var a=1;
      $.ajax({
          type: "GET",
          url: "services/",
          data: "act=generate-json&a="+tablename,
          success: function(data){
            // alert(data);
            // var obj = JSON.parse(data);
            // alert(obj['id']);
            //   var idPP = obj['kdproduct'];
            //   $('#imagePP').attr('src',imagePP);
          },
          error: function(data){

          }
      });
    }                