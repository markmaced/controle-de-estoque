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

  let products = [];
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
      console.log('clicou')
      activeBarcode = !activeBarcode ? true : false
    }
  })

  $(document).keydown(function(e) {
    console.log(activeBarcode)
    if (e.which >= 48 && e.which <= 57 && activeBarcode && $('.modal').attr('type') == '') {
        e.preventDefault()
        barcode += String.fromCharCode(e.which);
        if (barcode.length === 13) {
          console.log(barcode)
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
        console.log(JSON.stringify(response, null, 2));
        if (response.success === false) {
          alert(response.message);
          return;
        }
        // barcode = $('#manualBarcode').val();
        var productInfos = $('.product-infos[data-barcode="' + barcode + '"]');
        var productIndex = response.data.products.findIndex(function(product) {
          return product.barcode === barcode;
        });

        if (productInfos.length > 0) {
          var quantity = parseInt(productInfos.find('.table-content.quantity').text()) + 1;
          var unityPrice = parseFloat(response.data.products[productIndex].unity_price)
          var totalPrice = unityPrice * quantity;
          productInfos.find('.table-content.quantity').text(quantity);
          productInfos.find('.total-price').text(formatPrice(totalPrice));
        } else {
          var totalPrice = response.data.products[productIndex].total_price
          var row = '<div class="row product-infos" data-barcode="' + response.data.products[productIndex].barcode + '">' +
          '<div class="col-1"><p class="table-content quantity">1</p></div>' +
          '<div class="col-3"><p class="table-content">' + response.data.products[productIndex].barcode + '</p></div>' +
          '<div class="col-5"><p class="table-content">' + response.data.products[productIndex].title + '</p></div>' +
          '<div class="col-3"><div class="row"><div class="col-9"><p class="table-content total-price">' + formatPrice(totalPrice) + '</p></div><div class="col-3 trash"><img src="/wp-content/themes/controle-de-estoque/assets/images/trash.png"></div></div></div>' +
          '</div>';
          $('.column-content.table-list').append(row);
        }
      $('.total-price-card').text(formatPrice(total));
      $('.barcode-label').text(response.data.products[productIndex].barcode)
      $('.unity-price').text(formatPrice(response.data.products[productIndex].unity_price))
      $('#manualBarcode').val('');
      },
      error: function(xhr, status, error) {
        alert('Erro ao adicionar o produto.');
      }
    });
  }

  // Formatar preço
  function formatPrice(value) {
    var formattedValue = parseFloat(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    return formattedValue;
  }

  // Remover produto

  $(document).on('click', '.trash img', function() {
    // obter o código de barras do produto
    var barcode = $(this).closest('.product-infos').data('barcode');
    
    // encontrar o índice do produto no array de produtos
    var index = -1;
    for (var i = 0; i < products.length; i++) {
      if (products[i].barcode === barcode) {
        index = i;
        break;
      }
    }
  
    // remover o produto do array
      products.splice(index, 1);

      let newList = products
    
    // atualizar a exibição da tabela e do preço total
    updateTableAndTotalPrice(newList);
  });

  function updateTableAndTotalPrice(newList) {
    // limpar a tabela
    $('.product-list').empty();
    
    // iterar sobre os produtos e adicioná-los à tabela
    for (var i = 0; i < newList.length; i++) {
      var product = newList[i];
      
      var row = '<div class="row product-infos" data-barcode="' + product.barcode + '">' +
                  '<div class="col-1"><p class="table-content quantity">' + product.quantity + '</p></div>' +
                  '<div class="col-3"><p class="table-content">' + product.barcode + '</p></div>' +
                  '<div class="col-5"><p class="table-content">' + product.title + '</p></div>' +
                  '<div class="col-3"><div class="row"><div class="col-9"><p class="table-content total-price">R$&nbsp;' + product.total_price.toFixed(2) + '</p></div><div class="col-3 trash"><img src="/wp-content/themes/controle-de-estoque/assets/images/trash.png"></div></div></div>' +
                '</div>';
      
      $('.product-list').append(row);
    }

    console.log(newList)
    console.log(total)
    // atualizar o preço total
    $('.total-price-card').text(formatPrice(total));
  }

  // finalizar compra

  function createOrder(){
    if (products.length === 0) {
      alert('Nenhum produto no carrinho');
      return;
    }

    console.log(products)
    console.log(total)

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
        $('.modal').attr('type' , 'initial')
        console.log(response)
      },
      error: function(xhr, status, error) {
        alert('Erro ao efetuar a venda');
      }
    });
    
  }

  // Lista ceasa
  $('#download').on('click' , function(e){
    e.preventDefault()
    $.ajax({
      url: wpurl.ajax,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'generate_list_page',
      },
      success: function(response) {
        console.log(response)
      },
      error: function(xhr, status, error) {
        console.log(error)
        alert('Erro ao Baixar o arquivo');
      }
    });
  })
});