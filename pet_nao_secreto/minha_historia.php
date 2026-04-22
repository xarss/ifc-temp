<?php
require_once 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: pet_nao_secreto.php');
    exit();
}

header('Content-Type: text/html; charset=utf-8');

$id = $_GET['id'];
$sql = "SELECT * FROM animais WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$animal = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$animal) {
    header('Location: pet_nao_secreto.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($animal['nome']); ?> - E sua história</title>
    <!-- SEO Insituto Fica Comigo Início -->
    <meta name="author" content="Instituto Fica Comigo">
    <meta name="description" content="Pet Não Secreto De Natal │ Neste Natal, o Pet não será secreto e queremos que você conheça a história que trouxe o animal que vai receber o seu carinho até aqui.
    Que saiba de onde ele veio, o que enfrentou e o quanto sonha em sentir o amor de uma família.
    Cada história carrega marcas, mas também esperança. E é por isso que antes de escolher qual cão presentear (ou quem sabe adotar), convidamos você a ler todas as histórias, se emocionar e se conectar com seu escolhido.
    No final de cada história, deixamos a lista de presentes e seus valores... para que, com um simples Pix, você possa fazer a diferença mesmo na correria do dia a dia.
    Porque o Natal é sobre amor e este é o melhor presente que eles podem ganhar. ❤️">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Instituto Fica Comigo, resgate de animais, animais resgatados, maus-tratos com animais, vira-latas, pets, animais, animais domésticos, cachorros, cães, gatos, adoção de pets, adoção de cachorros, adoção de gatos, veterinários, loja virtual">
<!-- OPEN GRAPH FACEBOOK --> 
    <meta property="og:title" content="Instituto Fica Comigo">
    <meta property="og:description" content="Pet Não Secreto De Natal │ Neste Natal, o Pet não será secreto e queremos que você conheça a história que trouxe o animal que vai receber o seu carinho até aqui.
    Que saiba de onde ele veio, o que enfrentou e o quanto sonha em sentir o amor de uma família.
    Cada história carrega marcas, mas também esperança. E é por isso que antes de escolher qual cão presentear (ou quem sabe adotar), convidamos você a ler todas as histórias, se emocionar e se conectar com seu escolhido.
    No final de cada história, deixamos a lista de presentes e seus valores... para que, com um simples Pix, você possa fazer a diferença mesmo na correria do dia a dia.
    Porque o Natal é sobre amor e este é o melhor presente que eles podem ganhar. ❤️">
    <meta property="og:url" content="https://insitutoficacomigo.org.br">
    <meta property="og:site_name" content="Instituto Fica Comigo">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo htmlspecialchars($animal['imagem_url']); ?>">
<!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-icons.css" rel="stylesheet">
    <link href="../magnific-popup.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="icon" href="../images/logo_fav.png" type="image/png">
</head>
<body class="projects-detail-page" id="section_1">
<!-- Redes sociais início --> 
<header class="site-header">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-3 col-md-3 col-12">
                <ul class="social-icon d-flex justify-content-center">
                    <li><a href="https://www.instagram.com/institutoficacomigo_/" class="social-icon-link bi-instagram" target="_blank"></a></li>
                    <li><a href="https://www.tiktok.com/@institutoficacomigo_" class="social-icon-link bi-tiktok" target="_blank"></a></li>
                    <li><a href="https://www.facebook.com/InstitutoFicaComigo" class="social-icon-link bi-facebook" target="_blank"></a></li>
                    <li><a href="https://www.youtube.com/@InstitutoFicaComigo" class="social-icon-link bi-youtube" target="_blank"></a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<!-- Redes sociais fim -->
<section class="projects section-padding" id="#section_2">
    <div class="container">
        <a href="pet_nao_secreto.php" class="btn btn-primary custom-btn mt-2">&larr; Voltar para a galeria</a><br>
        <hr>
        <h2 class="mb-3" style="text-align:center;">Minha História</h2>
        <div class="row">
            <div class="col-lg-4 col-12 mb-5">
                <img class="img-fluid projects-image" alt="" style="width: 500px;" src="<?php echo htmlspecialchars($animal['imagem_url']); ?>" alt="<?php echo htmlspecialchars($animal['nome']); ?>">
                <h3 class="mb-3" style="text-align:center;"><?php echo htmlspecialchars($animal['nome']); ?></h3>
            </div> 
            <div class="col-lg-8 col-12 mb-5">
                
                <p><?php 
   $historia = mb_convert_encoding($animal['historia'], 'UTF-8', 'UTF-8');
   echo nl2br(htmlspecialchars($historia, ENT_QUOTES | ENT_HTML5, 'UTF-8')); 
?></p><hr>
                <p>Gostou da minha história? Abaixo está a minha lista de presentes!<br>
                Para deixar mais fácil, você também pode enviar seu presente via PIX.</p>
                <p style="text-align: left;">
                1. Ração adulto UNNA carne e arroz 20kg - R$129,95<br>
                2. Ração filhote UNNA carne e arroz 20kg - R$188,00<br>
                3. BRAVECTO 20-40kg - R$193,80<br>
                4. SIMPARIC 20-40kg - R$113,99<br>
                5. KIT LIMPEZA CANIL - R$100,00<br>
                6. KIT HIGIENE PET - R$120,00<br>
                </p><br>
            
                <a href="https://wa.me/41996949566?text=<?php echo urlencode('Olá, gostaria de presentear o ' . $animal['nome'] . ' nesse Natal! Como posso ajudar?'); ?>" class="btn btn-primary custom-btn mt-2" target="_blank">
               Presentear o <?php echo htmlspecialchars($animal['nome']); ?> pelo WhatsApp</a>
            </div>                        
        </div><hr>
    </div>
</section>
<!-- Footer início -->
<section class="contact" id="section_5">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#f9c10b" fill-opacity="1" d="M0,288L48,272C96,256,192,224,288,197.3C384,171,480,149,576,165.3C672,181,768,235,864,250.7C960,267,1056,245,1152,250.7C1248,256,1344,288,1392,304L1440,320L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
<div class="contact-container-wrap">
    <div class="container">
        <div class="row">                    
            <div class="col-lg-6 col-12">
                <form class="custom-form contact-form" role="form">
                    <small class="small-title">Entre em <strong class="text-white">contato</strong></small>
                        <h2 class="mb-5">Fale conosco</h2>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">                                    
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Digite seu nome" required="">
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">         
                                    <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Digite seu email" required="">
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" rows="7" id="message" name="message" placeholder="Digite sua mensagem"></textarea>
                                    <button type="submit" class="form-control">Enviar</button>
                                </div>
                            </div>
                </form>
            </div>
            <div class="col-lg-6 col-12">
                <div class="contact-thumb">                                    
                    <div class="contact-info bg-white shadow-lg">
                        <h4 class="mb-4">Junte-se a nós nessa corrente do bem.</h4>
                        <h5 >Faça uma doação ou apadrinhe o Instituto Fica Comigo!</h5>
                        <a class="custom-btn btn" href="faca_uma_doacao.html" style="margin-top: 20px; margin-bottom: 20px;">Faça uma Doação</a>
                        <h4 class="mb-2">
                            <a href="tel: (41) 3093-7879"><i class="bi-telephone contact-icon me-2"></i>(41) 3093-7879</a>
                        </h4>
                        <h5>
                                <a href="mailto:resgatesifc@gmail.com" class="footer-link"><i class="bi-envelope-fill contact-icon me-2"></i>resgatesifc@gmail.com</a>
                            </h5>

 <!-- Copy "embed a map" HTML code from any point on Google Maps -> Share Link  -->
                        <!-- 
                        <iframe class="google-map mt-4" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3602.6191437951925!2d-49.241918899999995!3d-25.4509935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dce54e3adc10cf%3A0x4344cf275f134ab5!2sCl%C3%ADnica%20Veterin%C3%A1ria%20Fica%20Comigo!5e0!3m2!1spt-BR!2sbr!4v1736355463619!5m2!1spt-BR!2sbr" width="100%" height="300" allowfullscreen="" loading="lazy"></iframe>   
                        -->          
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- Footer fim -->
</main>
<!-- SubFooter início -->        
<footer class="site-footer">
    <div class="container" style="text-align: center;">                          
        <p class="copyright-text mb-0 me-4">Copyright © 2025 Insituto Fica Comigo - Curitiba, PR</p><br>            
            <ul class="social-icon">
                <li><a href="https://www.instagram.com/institutoficacomigo_/" class="social-icon-link bi-instagram" target="_blank"></a></li>
                <li><a href="https://www.tiktok.com/@institutoficacomigo_" class="social-icon-link bi-tiktok" target="_blank"></a></li>    
                <li><a href="https://www.facebook.com/InstitutoFicaComigo" class="social-icon-link bi-facebook" target="_blank"></a></li>    
                <li><a href="https://www.youtube.com/@InstitutoFicaComigo" class="social-icon-link bi-youtube" target="_blank"></a></li>
            </ul><br>          
        <p class="copyright-text mb-0 me-4">Este site foi desenvolvido por <a href="https://www.jinggaweb.com.br" target="_blank">JinggaWeb</a></p>       
    </div>
</footer>
<!-- SubFooter fim -->
<!-- JAVASCRIPT FILES -->
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery.sticky.js"></script>
<script src="../js/jquery.magnific-popup.min.js"></script>
<script src="../js/magnific-popup-options.js"></script>
<script src="../js/click-scroll.js"></script>
<script src="../js/custom.js"></script>
<script src="../js/contact-form.js"></script>
</body>
</html>
