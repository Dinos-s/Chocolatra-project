<template>
    <footer>
        <p>
            © {{ new Date().getFullYear() }} Chocolatra
        </p>
       <div>
            <a href="https://www.instagram.com/chocolatra.pe/" class="fa-brands fa-instagram" target="_blank"></a>
            <a  href="https://wa.me/5581981187248" class="fa-brands fa-whatsapp"></a>
        </div>
    </footer>
</template>

<style scoped>
footer {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 20px;
    margin-top: 50px;
    background-color: #f3e8e8;
    border-radius: 45px 45px 0 0;
    color: #8a7866;
    font-size: 0.9rem;
    text-align: center;
}

footer div {
    display: flex;
    gap: 10px;
}

a {
    text-decoration: none;
    font-size: 20px;
    color: black;
}

footer a:hover {
    color: chocolate;
}
</style>