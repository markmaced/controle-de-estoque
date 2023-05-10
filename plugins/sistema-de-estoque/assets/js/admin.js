jQuery(document).ready(function($) {
// daily-report
$(document).on('click' , '.button-date' , function(e){
    console.log('clicou')
    e.preventDefault()
    var selectedDate = $('#date').val();
    console.log(selectedDate)
    var date = new Date(selectedDate);
    console.log(date)
    date.setDate(date.getDate() + 1);
    var tomorrow = date.toISOString().slice(0, 10);

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
      },
      error: function(xhr, status, error) {
        
      }
    });
  })
})