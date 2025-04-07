# Usa uma imagem oficial do PHP
FROM php:8.1-cli

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos do projeto para dentro do container
COPY . .

# Define o comando para iniciar o servidor
CMD ["php", "-S", "0.0.0.0:8080"]