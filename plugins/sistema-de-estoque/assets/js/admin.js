jQuery(document).ready(function($) {
// daily-report
$(document).on('change' , '#date' , function(e){
    e.preventDefault()
    var selectedDate = $('#date').val();
    
    // daily
    var dailyDate = new Date(selectedDate);
    dailyDate.setDate(dailyDate.getDate() + 1);
    var tomorrow = dailyDate.toISOString().slice(0, 10);

    // week 
    var weekDate = new Date(selectedDate)
    const dayOfWeek = weekDate.getDay();
    const startOfWeek = new Date(weekDate);
    startOfWeek.setDate(weekDate.getDate() - dayOfWeek);
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);

    const startDateWeekFormatted = startOfWeek.toISOString().slice(0, 10);
    const endDateWeekFormatted = endOfWeek.toISOString().slice(0, 10);
    // month

    const yearDate = new Date(selectedDate)
    const year = yearDate.getFullYear();
    const month = yearDate.getMonth();

    // Criar uma nova data representando o primeiro dia do mês
    const startOfMonth = new Date(year, month, 1);

    // Criar uma nova data representando o último dia do mês
    const endOfMonth = new Date(year, month + 1, 0);

    // Formatar as datas no formato "Y-m-d"
    const startDateFormatted = startOfMonth.toISOString().slice(0, 10);
    const endDateFormatted = endOfMonth.toISOString().slice(0, 10);


    $.ajax({
      url: wpurl.ajax,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'daily_report',
        init_date: selectedDate,
        final_date: tomorrow
      },
      success: function(response) {
        $('.daily-total').text('Valor Total das Vendas: R$' + response.data.valor_da_venda)
        $('.nome-produto-daily').text(response.data.produto_mais_vendido.title)
        $('.barcode-produto-daily').text(response.data.produto_mais_vendido.barcode)
        $('.quantity-produto-daily').text(response.data.quantidade_mais_vendida)
        $('.total-produto-daily').text(response.data.produto_mais_vendido.unity_price * response.data.quantidade_mais_vendida)
      },
      error: function(xhr, status, error) {
      }
    });
    $.ajax({
      url: wpurl.ajax,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'week_report',
        init_date: startDateWeekFormatted,
        final_date: endDateWeekFormatted
      },
      success: function(response) {
        $('.week-total').text('Valor Total das Vendas: R$' + response.data.valor_da_venda)
        $('.nome-produto-week').text(response.data.produto_mais_vendido.title)
        $('.barcode-produto-week').text(response.data.produto_mais_vendido.barcode)
        $('.quantity-produto-week').text(response.data.quantidade_mais_vendida)
        $('.total-produto-week').text(response.data.produto_mais_vendido.unity_price * response.data.quantidade_mais_vendida)
      },
      error: function(xhr, status, error) {
        
      }
    });
    $.ajax({
      url: wpurl.ajax,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'month_report',
        init_date: startDateFormatted,
        final_date: endDateFormatted
      },
      success: function(response) {
        $('.month-total').text('Valor Total das Vendas: R$' + response.data.valor_da_venda)
        $('.nome-produto-month').text(response.data.produto_mais_vendido.title)
        $('.barcode-produto-month').text(response.data.produto_mais_vendido.barcode)
        $('.quantity-produto-month').text(response.data.quantidade_mais_vendida)
        $('.total-produto-month').text(response.data.produto_mais_vendido.unity_price * response.data.quantidade_mais_vendida)
      },
      error: function(xhr, status, error) {
        
      }
    });
  })
  // Lista ceasa
  $('#download').on('click', function(e) {
    e.preventDefault();
    $.ajax({
      url: wpurl.ajax,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'generate_list_ceasa',
      },
      success: function(response) {
        // Redirecionar para a URL do PDF para iniciar o download
        window.location.href = response.pdfUrl;
      },
      error: function(xhr, status, error) {
        console.log(error);
        alert('Erro ao baixar o arquivo');
      }
    });
  });
})