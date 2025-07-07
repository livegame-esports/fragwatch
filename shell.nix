let 
  pkgs = import <nixpkgs> { };
in

pkgs.mkShell {
  buildInputs = with pkgs; [
    php84
    php84Packages.composer
  ];

  shellHook = ''
    echo "PHP 8.4 environment is set up."
  '';
}
