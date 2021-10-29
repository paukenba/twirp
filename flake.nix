{
  description = "Twirp PHP port";

  inputs = {
    nixpkgs.url = "nixpkgs/nixos-unstable";
    flake-utils.url = "github:numtide/flake-utils";
    flake-compat = {
      url = "github:edolstra/flake-compat";
      flake = false;
    };
  };

  outputs = { self, nixpkgs, flake-utils, ... }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = import nixpkgs { inherit system; };
        clientcompat = pkgs.buildGoPackage rec {
          pname = "clientcompat";
          version = "5.12.1";

          goPackagePath = "github.com/twitchtv/twirp";

          src = pkgs.fetchFromGitHub {
            owner = "twitchtv";
            repo = "twirp";
            rev = "v${version}";
            sha256 = "OXBeaoANUjh+s5baO0mw4zyVAFtn+VHMZgfxVNuUvAI=";
          };

          subPackages = [ "clientcompat" ];
        };
      in {
        devShell = pkgs.mkShell {
          buildInputs = with pkgs;
            [
              git
              gnumake
              go
              php
              protobuf
              php.packages.composer
              golangci-lint
              gotestsum
              goreleaser
            ] ++ [ clientcompat ];
        };
      });
}