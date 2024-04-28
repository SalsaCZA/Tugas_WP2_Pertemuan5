<?php

class Book {
    public $id;
    public $judul;
    public $penulis;
    public $tahun;
    public $pinjam;

    public function __construct($id, $judul, $penulis, $tahun) {
        $this->id = $id;
        $this->judul = $judul;
        $this->penulis = $penulis;
        $this->tahun = $tahun;
        $this->pinjam = false;
    }

    public function pinjamBuku() {
        if (!$this->pinjam) {
            $this->pinjam = true;
            return true;
        } else {
            return false; // Buku sedang dipinjam
        }
    }

    public function pengembalianBuku() {
        if ($this->pinjam) {
            $this->pinjam = false;
            return true;
        } else {
            return false; // Buku tidak dipinjam
        }
    }
}

class ReferenceBook extends Book {
    public $isbn;
    public $penerbit;

    public function __construct($id, $judul, $penulis, $tahun, $isbn, $penerbit) {
        parent::__construct($id, $judul, $penulis, $tahun);
        $this->isbn = $isbn;
        $this->penerbit = $penerbit;
    }
}

class Library {
    private $maxPeminjaman = 3; // Batasan jumlah buku yang dapat dipinjam
    private $dendaPerHari = 500; // Denda per hari keterlambatan
    public $books = [];

    public function tambahBuku($book) {
        $this->books[] = $book;
    }

    public function cariBuku($keyword) {
        $searchResults = [];
        foreach ($this->books as $book) {
            if ($book instanceof Book && (stripos($book->judul, $keyword) !== false || stripos($book->penulis, $keyword) !== false)) {
                $searchResults[] = $book;
            }
        }
        return $searchResults;
    }

    public function pinjamBukuRegular($judul) {
        $jumlahBukuDipinjam = count(array_filter($this->books, function($book) {
            return $book->pinjam;
        }));
        if ($jumlahBukuDipinjam >= $this->maxPeminjaman) {
            return "Maaf, Anda telah mencapai batas peminjaman buku.";
        }

        foreach ($this->books as $book) {
            if ($book instanceof Book && $book->judul == $judul && !$book->pinjam) {
                return $book->pinjamBuku();
            }
        }
        return false; // Buku tidak ditemukan atau sedang dipinjam
    }

    public function pinjamBukuReference($judul) {
        // Add logic for reference book borrowing here
    }

    public function pengembalianBuku($judul) {
        foreach ($this->books as $book) {
            if ($book instanceof Book && $book->judul == $judul && $book->pinjam) {
                return $book->pengembalianBuku();
            }
        }
        return false; // Buku tidak ditemukan atau tidak dipinjam
    }

    public function ketersediaanBuku() {
        $ketersediaanBuku = [];
        foreach ($this->books as $book) {
            if ($book instanceof Book && !$book->pinjam) {
                $ketersediaanBuku[] = $book->judul;
            }
        }
        return $ketersediaanBuku;
    }

    public function urutkanBuku($kriteria) {
        $sortedBooks = $this->books;
        if ($kriteria == 'tahun') {
            usort($sortedBooks, function($a, $b) {
                return $a->tahun - $b->tahun;
            });
        } else if ($kriteria == 'penulis') {
            usort($sortedBooks, function($a, $b) {
                return strcmp($a->penulis, $b->penulis);
            });
        }
        return $sortedBooks;
    }

    public function hitungDenda($judul, $hariTerlambat) {
        foreach ($this->books as $book) {
            if ($book instanceof Book && $book->judul == $judul && $book->pinjam) {
                $totalDenda = $hariTerlambat * $this->dendaPerHari;
                return $totalDenda;
            }
        }
        return false; // Buku tidak ditemukan atau tidak dipinjam
    }

    public function hapusBuku($idBuku) {
        foreach ($this->books as $key => $book) {
            if ($book->id == $idBuku) {
                unset($this->books[$key]);
                return true; // Buku berhasil dihapus
            }
        }
        return false; // Buku tidak ditemukan
    }
}

// Membuat objek buku
$book1 = new Book(1, "Harry Potter", "J.K. Rowling", 1997);
$book2 = new Book(2, "The Great Gatsby", "F. Scott Fitzgerald", 1925);
$book3 = new Book(3, "To Kill a Mockingbird", "Harper Lee", 1960);
$book4 = new Book(4, "1984", "George Orwell", 1949);
$book5 = new Book(5, "Pride and Prejudice", "Jane Austen", 1813);
$book6 = new Book(6, "The Catcher in the Rye", "J.D. Salinger", 1951);
$book7 = new Book(7, "The Lord of the Rings", "J.R.R. Tolkien", 1954);
$book8 = new Book(8, "The Hobbit", "J.R.R. Tolkien", 1937);
$book9 = new ReferenceBook(9, "The Da Vinci Code", "Dan Brown", 2003, "9780307474278", "Doubleday");
$book10 = new ReferenceBook(10, "The Alchemist", "Paulo Coelho", 1988, "9780062315007", "HarperOne");

// Membuat objek perpustakaan
$library = new Library();

// Menambahkan buku ke perpustakaan
$library->tambahBuku($book1);
$library->tambahBuku($book2);
$library->tambahBuku($book3);
$library->tambahBuku($book4);
$library->tambahBuku($book5);
$library->tambahBuku($book6);
$library->tambahBuku($book7);
$library->tambahBuku($book8);
$library->tambahBuku($book9);
$library->tambahBuku($book10);


echo "\n------------------------------------------------------------------------------\n";
echo "----------------------------- PEMINJAMAN BUKU ---------------------------------\n\n";
// Meminjam buku
echo $library->pinjamBukuRegular("The Hobbit") ? "Buku berhasil dipinjam." : "Buku tidak tersedia atau sudah dipinjam.";
echo "\n";


echo "\n------------------------------------------------------------------------------\n";
echo "----------------------------- PENGEMBALIAN BUKU ---------------------------------\n\n";
// Mengembalikan buku
echo $library->pengembalianBuku("Harry Potter") ? "Buku berhasil dikembalikan." : "Buku tidak ditemukan atau tidak sedang dipinjam.";
echo "\n";


echo "\n------------------------------------------------------------------------------\n";
echo "----------------------------- PENCARIAN BUKU ---------------------------------\n\n";
// Mencari buku
$searchResults = $library->cariBuku("Great");
echo "Hasil pencarian buku:\n";
foreach ($searchResults as $book) {
    echo "{$book->judul} - {$book->penulis} ({$book->tahun})\n";
}


echo "\n------------------------------------------------------------------------------\n";
echo "----------------------------- PENGURUTAN BUKU ---------------------------------\n\n";
// Mengurutkan buku
$sortedBooks = $library->urutkanBuku('tahun');
echo "Daftar buku berdasarkan tahun terbit:\n";
foreach ($sortedBooks as $book) {
    echo "{$book->judul} - {$book->penulis} ({$book->tahun})\n";
}


echo "\n------------------------------------------------------------------------------\n";
echo "----------------------------- PENGHITUNGAN BUKU ---------------------------------\n\n";
// Menghitung denda
$denda = $library->hitungDenda("Harry Potter", 5);
echo "Denda keterlambatan: Rp$denda\n";


echo "\n------------------------------------------------------------------------------\n";
echo "----------------------------- PENGHAPUSAN BUKU ---------------------------------\n\n";
// Menghapus buku
echo $library->hapusBuku(3) ? "Buku berhasil dihapus." : "Buku tidak ditemukan.";
echo "\n";


echo "\n------------------------------------------------------------------------------\n";
echo "----------------------------- KETERSEDIAAN BUKU ---------------------------------\n\n";
// Mencetak daftar buku yang tersedia
$ketersediaanBuku = $library->ketersediaanBuku();
echo "Buku yang tersedia di perpustakaan: " . implode(", ", $ketersediaanBuku);
?>
