jQuery(document).ready(function($) {

  //modal
  $(document).keydown(function(event) {
    if (event.altKey && event.keyCode === 49) {
        let text = $('#payment').attr('placeholder')
      $(".modal").attr("hidden", false).addClass("show");
      $(".modal input[function='payment']").val('');
      $('.modal').attr("type" , "payment")
      $('.modal label').text(text)
      $('#confirm').css('display' , 'block')
      $('.modal label').css('display' , 'block')
    } else if (event.altKey && event.keyCode === 50) {
        let text = $('#manualBarcode').attr('placeholder')
      $(".modal").attr("hidden", false).addClass("show");
      $(".modal input[function='manualBarcode']").val('');
      $('.modal').attr("type" , "manualBarcode")
      $('.modal label').text(text)
      $('#confirm').css('display' , 'block')
      $('.modal label').css('display' , 'block')
    } else if (event.altKey && event.keyCode === 51){
      $(".modal").attr("hidden", false).addClass("show");
      $('.modal').attr("type" , "checkout")
      $('#confirm').css('display' , 'none')
      $('.modal label').css('display' , 'none')
    }
  });

  $(document).keydown(function(event) {
    if (event.key === "Escape" && $(".modal").is(":visible")) {
      $(".modal").removeClass("show").attr("hidden", true);
      $("body").removeClass("modal-overlay");
    }
  });

  $(document).keydown(function(event) {
    if (event.key === "Escape" && $(".modal").is(":visible")) {
      $(".modal").removeClass("show").attr("hidden", true);
      $("body").removeClass("modal-overlay");
      $(".modal").attr("type", '');
    }
  });

    //Valor recebido
    function confirmPayment() {
        if($('.modal').attr('type') === 'payment'){
          var paymentValue = $("#payment").val();
          if(paymentValue != ''){
            var preco = $(".total-price-card").text().replace("R$","");
            var troco = paymentValue.replace(',', '.') - parseFloat(preco.replace(',', '.'))
            $("#price-card").text(formatPrice(paymentValue));
            $(".change-value").text(formatPrice(troco))
            $(".modal").removeClass("show").attr("hidden", true);
          }else{
            alert('Valor inválido')
          }
        }
    }
    $(document).keypress(function(event) {
        if (event.keyCode === 13 && $(".modal").hasClass("show")) {
            confirmPayment();
            $(".modal").attr("type", '');
            barcode = ''
        }
    });
    $(document).on('click' , '#confirm' , function(event) {
        confirmPayment();
        $(".modal").attr("type", '');
        barcode = ''
    });

  window.products = []
  let products = window.products
  let total = 0
  let barcode = ''
  let activeBarcode = false

  $('#confirm').on('click', function() {
    if($('.modal').attr('type') === 'manualBarcode'){
      barcode = $('#manualBarcode').val();
      addProduct(barcode);
      $(".modal").removeClass("show").attr("hidden", true);
    }
  });

  $(document).keydown(function(e){
    if(e.ctrlKey){
      activeBarcode = !activeBarcode ? true : false

      activeBarcode === true ? $('.barcode-indicator').css({'background' : 'green' , 'width' : '20px' , 'height' : '20px' , 'border-radius' : '50px'}) : $('.barcode-indicator').css({'background' : 'red' , 'width' : '20px' , 'height' : '20px' , 'border-radius' : '50px'})
    }
  })

  $(document).keydown(function(e) {
    if (e.which >= 48 && e.which <= 57 && activeBarcode && $('.modal').attr('type') == '') {
        e.preventDefault()
        barcode += String.fromCharCode(e.which);
        if (barcode.length === 13) {
            addProduct(barcode)
            barcode = '';
        }
    }
});
  
  $('#manualBarcode').keypress(function(e) {
    if (e.which == 13 && $('.modal').attr('type') === 'manualBarcode') {
      barcode = $('#manualBarcode').val();
      addProduct(barcode);
      $(".modal").removeClass("show").attr("hidden", true);
    }
  });


  $('#createOrder').on('click', function() {
    if($('.modal').attr('type') === 'checkout'){
      createOrder()
      $(".modal").removeClass("show").attr("hidden", true);
    }
  });

  
  $('#checkout').keypress(function(e) {
    if (e.which == 13 && $('.modal').attr('type') === 'checkout') {
      createOrder()
      $(".modal").removeClass("show").attr("hidden", true);
    }
  });

  // adicionar produto
  
  function addProduct(barcode) {
    if (barcode === '') {
        alert('Digite um código de barras válido.');
        return;
    }

    $.ajax({
        url: wpurl.ajax,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'add_product',
            barcode: barcode
        },
        success: function(response) {
            products = response.data.products
            total = response.data.total_price
            if (response.success === false) {
                alert(response.message);
                return;
            }
            var productIndex = response.data.products.findIndex(function(product) {
                return product.barcode === barcode;
            });

            var pricePerKg = response.data.products[productIndex].price_per_kg;
            if (pricePerKg === 'Sim') {
                openModalAndCalculatePrice(response.data.products[productIndex] , productIndex);
            } else {
                updateTableAndTotalPrice(products);
                $('.barcode-label').text(response.data.products[productIndex].barcode);
                $('.unity-price').text(formatPrice(response.data.products[productIndex].unity_price));
                $('#manualBarcode').val('');
            }
        },
        error: function(xhr, status, error) {
            alert('Erro ao adicionar o produto.');
        }
    });
}

function openModalAndCalculatePrice(product, productIndex) {
  $(".modal").attr("hidden", false).addClass("show");
  $('.modal').attr("type", "kg")
  $('label').text('Digite o peso em gramas')
  if ($('.modal').attr('type') == 'kg') {
      $('#confirm').on('click', function (e) {
          e.preventDefault();
          let weight = $('#pricePerKg').val() / 1000;
          let priceWeight = weight * product.unity_price;

          $.ajax({
              url: wpurl.ajax,
              type: 'POST',
              dataType: 'json',
              data: {
                  action: 'price_per_weight',
                  productIndex: productIndex,
                  total_price: priceWeight
              },
              success: function (response) {
                $(".modal").attr("hidden", true).removeClass("show");
                $('.modal').attr("type", "")
                  products = response.data.products;
                  total = response.data.total_price;
                  updateTableAndTotalPrice(products);
                  $('.barcode-label').text(response.data.products[productIndex].barcode);
                  $('.unity-price').text(formatPrice(response.data.products[productIndex].unity_price));
                  $('#manualBarcode').val('');
              },
              error: function (xhr, status, error) {
                  alert('Erro ao adicionar o produto.');
              }
          });
      });
  }
}

  // Formatar preço
  function formatPrice(value) {
    var formattedValue = parseFloat(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    return formattedValue;
  }

  // Remover produto

  $(document).on('click', '.trash img', function() {
    var dataBarcode = $(this).closest('.product-infos').data('barcode');
    $.ajax({
      url: wpurl.ajax,
      type: 'POST',
      dataType: 'json',
      data: {
      action: 'delete_product',
      barcode: dataBarcode
    },
    success: function(response) {
      products = response.data.products
      total = response.data.total_price
      updateTableAndTotalPrice(products);
    },
    error: function(xhr, status, error) {
      alert('Erro ao excluir o produto.');
    }
    });
  });

function updateTableAndTotalPrice(products) {
  var total = 0;
  $('.column-content.table-list').empty();
  if(products.length != 0){
      for (var i = 0; i < products.length; i++) {
          var product = products[i];
          var row = '<div class="row product-infos" data-barcode="' + product.barcode + '">' +
                      '<div class="col-1"><p class="table-content quantity">' + product.quantity + '</p></div>' +
                      '<div class="col-3"><p class="table-content">' + product.barcode + '</p></div>' +
                      '<div class="col-5"><p class="table-content">' + product.title + '</p></div>' +
                      '<div class="col-3"><div class="row"><div class="col-9"><p class="table-content total-price">R$&nbsp;' + product.total_price.toFixed(2) + '</p></div><div class="col-3 trash"><img src="/wp-content/themes/controle-de-estoque/assets/images/trash.png"></div></div></div>' +
                  '</div>';
  
          $('.column-content.table-list').append(row);
          total += product.total_price;
      }
  }else{
      $('.column-content.table-list').text('');
  }
  // products = newProducts;
  $('.total-price-card').text(formatPrice(total));
}

  // finalizar compra

  function createOrder(){
    if (products.length === 0) {
      alert('Nenhum produto no carrinho');
      return;
    }

    $.ajax({
      url: wpurl.ajax,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'create_order',
        products: products,
        total: total
      },
      success: function(response) {
        products = []
        total = 0
        barcode = ''
        $('.column-content.table-list').text('')
        $('.unity-price').text('R$ 0,00')
        $('.barcode-label').html('<br>')
        $('.total-price-card').text('R$ 0,00')
        $('#price-card').text('R$ 0,00')
        $('.change-value').text('R$ 0,00')
        $('#payment').val('')
        alert('Venda efetuada')
        $('.modal').attr('type' , '')
      },
      error: function(xhr, status, error) {
        alert('Erro ao efetuar a venda');
      }
    });
    
  }
});