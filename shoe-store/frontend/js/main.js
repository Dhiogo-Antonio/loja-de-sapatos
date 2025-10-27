document.addEventListener("DOMContentLoaded", async () => {
  const container = document.querySelector(".container");

  try {
    const res = await fetch("http://localhost/shoe-store/backend/routes/products.php");
    const products = await res.json();

    container.innerHTML = products.map(p => `
      <div class="product-card">
        <img src="${p.image}" alt="${p.name}">
        <h3>${p.name}</h3>
        <p>${p.description}</p>
        <strong>R$ ${parseFloat(p.price).toFixed(2)}</strong><br>
        <button onclick="addToCart('${p.name}')">Adicionar ao Carrinho</button>
      </div>
    `).join('');
  } catch (error) {
    container.innerHTML = `<p>Erro ao carregar produtos 😢</p>`;
  }
});

function addToCart(name) {
  alert(`${name} adicionado ao carrinho!`);
}

let cart = [];

function addToCart(id) {
    // Se estiver usando array de produtos global no JS, pegue o produto
    // Para este exemplo simples, apenas adicionamos o ID
    cart.push(id);
    alert("🛒 Produto adicionado ao carrinho! Total de itens: " + cart.length);
}

// Função para listar carrinho (pode criar página carrinho.php futuramente)
function viewCart() {
    if(cart.length === 0){
        alert("Carrinho vazio 😢");
        return;
    }
    let message = "Itens no carrinho:\n";
    cart.forEach((item, index) => {
        message += `${index+1}. Produto ID: ${item}\n`;
    });
    alert(message);
}
