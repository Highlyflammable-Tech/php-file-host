# Php-file-host
A php file host which can be used with sharex
Based on https://github.com/Inteliboi/ShareX-Custom-Upload

Example of where it is being hosted:
https://files.highlyflammable.tech/

# Setup
1. Clone
2. Edit index.php:
   * Adding in your own tokens
   * Editing your response when a file isn't found or on the homepage
3. Upload to your host
4. Create a folder called `i`
5. Change `i` Chmod to be 770
6. Copy example.sxcu into sharex and edit to have you domain and tokens in

# Support
If you need support please join the [code::together discord server](https://together.codes/discord).
Then please ping me ♛ ᖴᒪᗩᙏᙏᗩᙖᒪᙓᗩSSᗩSSIᑎ® ♛#4701 in the php channel!

# Things that need to be done
- [ ] Add SQL database support
- [ ] Add file name checking
- [ ] A Sharex custom uploader file generator for either clipboard or file download
- [ ] Get a list of viewable files

# Ideas
 - Password protect a file
