## Português

# Redimensionador e compactador de imagens [PHP]

## Descrição:

Recria imagens com tamanho e qualidade passados por parâmetros. Também força o cache do navegador por 30 dias.

Ideal para sites e sistemas que não podem armazenar vários tamanhos de uma mesma imagem. Com este é possível passar por parâmetros o tamanho (largura e altura), o caminho da imagem, e a cor de fundo que será utilizado (esta cor de fundo serve para quando uma imagem não puder ser redimensionada proporcionalmente, será adicionado na largura ou altura esta cor, fazendo assim com que o resultado final tenha a largura e altura informado pelos parâmetros).

Uma ideia de utilização, é em sites que contem uma versão mobile separada da versão desktop e precisa diminuir o tamanho e peso das imagens sem ter que armazenar uma nova imagem para isso.

## Arquivos:

.htaccess (responsável pela URL amigável e força todas as requisições a serem redirecionadas para o arquivo “index.php” passando todos os itens após o diretório raiz deste projeto como a variável  “cod”);
index.php (arquivo responsável por executar todas as funções);
image.jpg (imagem de exemplo utilizada);
no-image.jpg (imagem que será carregada quando a imagem passada pelos parâmetros não for encontrada).

## Parâmetros:

`i` (endereço “http” da imagem);
`w` (largura da imagem);
`h` (altura da imagem);
`c` (cor de preenchimento da imagem).

## Exemplo de uso:

http://fagnerfernandes.com.br/exemplos/red-imagens/`imagem.jpg`?i=http://fagnerfernandes.com.br/exemplos/red-imagens/image.jpg&c=000000&w=200&h=450

O item marcado acima (imagem.jpg) pode ser qualquer nome, indicasse a utilização do nome real da imagem a fim de aumentar o SEO, porem qualquer nome é aceito.

## Licença

MIT


---------------------------------------------------------------------

## English

# Resizer and image compactor [PHP]

## Description:

Recreates images with size and quality parameters passed by. It also forces the browser cache for 30 days.

Ideal for websites and systems that can not store multiple sizes of the same image. With this it is possible to pass parameters size (width and height), the image path, and the background color to be used (this background color is for when an image can not be resized proportionally, will be added to the width or height this color, thereby making the end result has the width and height parameters informed by).

An idea of ​​use it on websites that contains a separate mobile version of the desktop version and need to reduce the size and weight of the images without having to store a new image for it.

## Files:

.htaccess (responsible for the friendly URL and force all requests to be redirected to the file "index.php" passing all items after the root directory of this project as the variable "cod");
index.php (file responsible for performing all functions);
image.jpg (sample image used);
no-image.jpg (image to be loaded when the last image the parameters is not found).

## Parameters:

`I` (address" http "of the image);
`W` (image width);
`H` (image height);
`C` (Image fill color).

## Example usage:

http://fagnerfernandes.com.br/exemplos/red-imagens/`imagem.jpg`?i=http://fagnerfernandes.com.br/exemplos/red-imagens/image.jpg&c=000000&w=200&h=450

The item marked up (image.jpg) can be any name, indicate the use of the actual name of the image in order to increase the SEO, put any name is accepted.

## License

MIT