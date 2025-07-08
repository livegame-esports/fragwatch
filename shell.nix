let 
  pkgs = import <nixpkgs> { };
in

pkgs.mkShell {
  buildInputs = with pkgs; [
    php81
    php81Packages.composer
  ];

  shellHook = ''
    echo "PHP 8.4 environment is set up."
  '';
}
