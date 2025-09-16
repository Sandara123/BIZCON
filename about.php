<?php include('layout/header.php');?>



<style>
   
   :root {
      --bg-light: #ffffff; 
      --bg-dark: #1a1a1a; 
      --text-light: #000000; 
      --text-dark: #ffffff; 
      --accent-light: #004d00; 
      --accent-dark: #79d279; 
      --card-bg-light: #ffffff; 
      --card-bg-dark: #2a2a2a; 
      --border-light: #cccccc;
      --border-dark: #444444;
      --shadow-light: rgba(0, 0, 0, 0.2);
      --shadow-dark: rgba(255, 255, 255, 0.2);
   }

  /* About Us Section Container */
.about-container {
   display: grid;
   grid-template-columns: 1fr 1fr;
   gap: 20px;
   margin: 30px auto;
   max-width: 1400px;
   padding: 30px;
   padding-top: 100px; /* Add extra padding at the top to create space */
   background-color: var(--card-bg-light); /* Ensure background adapts to mode */
   color: var(--text-light);
   border-radius: 10px;
   box-shadow: 0 4px 10px var(--shadow-light);
   font-family: 'Lato', Arial, sans-serif;
   transition: background-color 0.3s ease, color 0.3s ease;
}

   /* Text Content Styling */
   .text-content {
      grid-column: 1;
      text-align: justify;
   }

   .text-content h1 {
      font-size: 2.6rem;
      margin-bottom: 20px;
      color: var(--accent-light);
      font-weight: bold;
      text-transform: uppercase;
      border-bottom: 2px solid var(--accent-light);
      padding-bottom: 5px;
   }

   .text-content p {
      font-size: 1.2rem;
      line-height: 1.8;
      margin-bottom: 15px;
      color: var(--text-light);
   }

   .text-content p strong {
      font-size: 1.3rem;
      color: var(--accent-light);
   }

   /* Image Container Styling */
   .image-container {
      grid-column: 2;
      display: flex;
      flex-direction: column;
      gap: 15px;
   }

   .about-image,
   .about-image-second {
      width: 100%;
      height: 300px;
      border-radius: 10px;
      object-fit: cover;
      box-shadow: 0 4px 10px var(--shadow-light);
   }

   /* Third Section Styling */
   .third-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin: 20px auto;
      max-width: 1400px;
      padding-top: 10px;
      border-top: 1px solid var(--border-light);
   }

   .third-text-content p {
      font-size: 1.2rem;
      line-height: 1.8;
      color: var(--text-light);
   }

   .about-image-third {
      width: 100%;
      height: 350px;
      border-radius: 5px;
      object-fit: cover;
      box-shadow: 0 4px 10px var(--shadow-light);
   }

   /* Hover Effects */
   .about-image:hover,
   .about-image-second:hover,
   .about-image-third:hover {
      transform: scale(1.05);
      transition: transform 0.3s ease;
   }

   /* Responsive Design */
   @media (max-width: 1024px) {
      .about-container,
      .third-container {
         grid-template-columns: 1fr;
      }

      .about-image,
      .about-image-second,
      .about-image-third {
         height: auto;
      }
   }

   
   @media (prefers-color-scheme: dark) {
     
      .about-container {
         background-color: var(--card-bg-dark); 
         box-shadow: 0 4px 10px var(--shadow-dark);
      }

      .text-content h1 {
         color: var(--accent-dark);
         border-bottom: 2px solid var(--accent-dark);
      }

      .text-content p {
         color: var(--text-dark);
      }

      .text-content p strong {
         color: var(--accent-dark);
      }

      .third-container {
         border-top: 1px solid var(--border-dark);
      }
   }
</style>

<!-- About Us HTML Structure -->
<div id="about" class="content">
   <div class="about-container">
      <div class="text-content">
         <h1>About Us</h1>
         <p>
            <strong>Welcome to <span style="color: var(--accent-light);">BIZCON</span></strong><br><br>
            <strong>Bizcon Distribution Inc.</strong> is one of the largest distributors of a wide range of office supplies and high-quality print consumables in the Philippines. Serving both the consumer and commercial markets, the company offers an extensive selection of products, from everyday office essentials such as paper, pens, and folders, to specialized printing supplies like toners, inks, and high-end print media.
         </p>
         <p>
            With a commitment to <strong>quality and customer satisfaction</strong>, Bizcon Distribution Inc. has built strong relationships with leading global brands, ensuring that their clients have access to the latest products and innovations in the industry. Whether catering to small businesses, large corporations, or individual consumers, the company takes pride in providing reliable, efficient service and fast delivery across the country.
         </p>
         <p>
            Their comprehensive product range and dedication to excellence make them a <strong>trusted partner</strong> for businesses and professionals in need of top-tier office and printing solutions.
         </p>
      </div>
      <div class="image-container">
         <img src="assets/imgs/HAND_SHAKE.jpg" alt="Bizcon Distribution" class="about-image" />
         <img src="assets/imgs/SECOND_ABOUT US.png" alt="Bizcon Second Image" class="about-image-second" />
      </div>
   </div>
   <div class="third-container">
      <div class="third-text-content">
         <p>
            Furthermore, Bizcon Distribution Inc. places a strong emphasis on <strong>sustainability</strong> and eco-friendly practices. They offer a range of environmentally conscious products, such as recycled paper and energy-efficient printing solutions, reflecting their commitment to reducing environmental impact while supporting their clients in achieving their sustainability goals.
         </p>
         <p>
            Through years of industry expertise, innovative solutions, and an uncompromising focus on <strong>quality and service</strong>, Bizcon Distribution Inc. continues to be the go-to distributor for office and printing needs across the Philippines.
         </p>
      </div>
      <img src="assets/imgs/THIRD_ABOUT US.jpg" alt="Bizcon Third Image" class="about-image-third" />
   </div>
</div>














       <!---Footer-->
       <?php include('layout/footer.php');?>