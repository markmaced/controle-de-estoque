<header>
    <div class="row align-center justify-content-between align-items-center">
        <div class="col-4">
            <h2>Mercearia do André</h2>
        </div>
        <div class="col-8">
            <div class="d-flex justify-content-end" style="gap: 20px;">
                <p class="keyboard-info">ALT + 1 - Receber pagamento</p>
                <p class="keyboard-info"> / </p>
                <p class="keyboard-info">ALT + 2 - Digitar código</p>
            </div>
        </div>
    </div>
</header>
<section>
    <div class="content-infos">
        <div class="col-12 header-content">
            <h2>Informações do pedido</h2>
        </div>
        <div class="col-12">
            <div class="row" style="margin-top: 20px;">
                <div class="col-3 column">
                    <div class="column-card d-flex flex-column">
                        <div class="header-column">
                            <h3>Seja bem vindo</h3>
                        </div>
                        <div class="column-content" style="text-align: center;">
                            <p>Tenha um ótimo dia!</p>
                        </div>
                    </div>
                    <div class="column-card d-flex flex-column">
                        <div class="header-column">
                            <h3>Valor recebido</h3>
                        </div>
                        <div class="column-content">
                            <p id="price-card">R$0,00</p>
                        </div>
                    </div>
                    <div class="column-card d-flex flex-column" style="margin-bottom: 0;">
                        <div class="header-column">
                            <h3>Troco</h3>
                        </div>
                        <div class="column-content">
                            <p class="change-value">R$0,00</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="column-card d-flex flex-column">
                        <div class="header-column">
                            <h3>Código de barras</h3>
                        </div>
                        <div class="column-content">
                            <p class="barcode-label"><br></p>
                        </div>
                    </div>
                    <div class="column-card d-flex flex-column">
                        <div class="header-column">
                            <h3>Valor unitário</h3>
                        </div>
                        <div class="column-content">
                            <p class="unity-price">R$0,00</p>
                        </div>
                    </div>
                    <div class="column-card d-flex flex-column" style="margin-bottom: 0;">
                        <div class="header-column">
                            <h3>Total do carrinho</h3>
                        </div>
                        <div class="column-content">
                            <p class="total-price-card">R$0,00</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="column-card d-flex flex-column column-list">
                        <div class="header-column">
                            <div class="row">
                                <div class="col-1"><p class="table-title">QTD</p></div>
                                <div class="col-3"><p class="table-title">Código de barras</p></div>
                                <div class="col-5"><p class="table-title">Nome</p></div>
                                <div class="col-3"><p class="table-title">Preço</p></div>
                                <input type="hidden" value="">
                            </div>
                        </div>
                        <div class="column-content table-list"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" hidden type="">
        <label></label>
        <input id="payment" type="text" placeholder="Digite o valor recebido"></input>
        <input id="manualBarcode" type="text" placeholder="Digite o código de barras do produto"></input>
        <button id="confirm" type="button">Confirmar</button>
        <div id="checkout" type="checkout">
            <div class="checkout-title">
                <h2>Deseja realmente finalizar a compra?</h2>
            </div>
            <div class="checkout-btns">
                <button type="button" id="createOrder">Sim</button>
                <button type="button" id="back">Cancelar</button>
            </div>
        </div>
    </div> 
</section>
