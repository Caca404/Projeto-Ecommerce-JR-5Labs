# Projeto Ecommerce da 5Labs

## Descrição
É um projeto de uma loja de produtos com 3 tipos de usuários: Admin, Comprador e 
Vendendor, onde o admin gerencia os outros dois e os produtos.

1. Para iniciar o projeto, já deve ter composer e node instalado na máquina.
Após o git clone ser feito, deve-se criar um arquivo *.env*, copiar do *.env.example* e colocar as suas configurações de banco de dados.

2. Após a criação do .env, deve-se gerar a key que o Laravel precisa para funcionar.

        >> php artisan key:generate

3. Depois de configurado, é hora de instalar as dependências do composer e node com os seguintes comandos no terminal.

        >> php artisan serve

        >> npm install

4. Com todo o processo de instalação terminado, deve subir no banco de dados todas as migrates com seeders.

        >> php artisan migrate --seed

5. Por fim, para acessar o site tem dois casos: via Dev ou via build.
   1. No caso Dev, usamos os seguintes comandos em terminais diferentes:
   
            >> php artisan serve

            >> npm run dev

    2. No caso da Build, você precisará que essa aplicação Laravel esteja em um servidor, seja no Linux ou Windows, pois precisa dele para direcionar uma rota para a pasta */public*, não precisando que seja feito o comando *php artisan serve* acima. Para o Js, css e entre outras coisas que o Vite esteja cuidando, deve-se fazer o seguinte comando:

            >> npm run build