import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import { pickBy } from 'lodash'
import Swal from 'sweetalert2'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faPencil, faTrash, faFile } from '@fortawesome/free-solid-svg-icons'

export default function Kelas({ auth, kelas }) {
    const perpage = useRef(kelas.per_page)
    const [isloadong, setIsloading] = useState(false)
    const [cari, setCari] = useState('')

    useEffect(() => {
        import('@fortawesome/react-fontawesome')
        import("@lottiefiles/lottie-player")
    })

    const handleChangeValue = (e) => {
        perpage.current = e.target.value
        getData()
    }

    let IDR = new Intl.NumberFormat('id', {
        style: 'currency',
        currency: 'IDR',
    });

    const getData = () => {
        setIsloading(true)
        router.get(
            route().current(),
            pickBy({
                perpage: perpage.current,
                cari
            })
            , {
                preserveScroll: true,
                preserveState: true,
                onFinish: () => setIsloading(false)
            }
        )
    }

    const handlerSearch = (e) => {
        e.preventDefault()
        getData()
    }

    const handlerHapus = (id) => {
        Swal.fire({
            title: "Yakin Mehapus Data?",
            text: "Apakah Anda Yakin Ingin Menghapus Data Ini!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Saya Yakin!",
            cancelButtonText: "Batal"
          }).then((result) => {
            if (result.isConfirmed) {          
                router.get(route('kelas-hapusKelas',id))
            }
          });
        getData()
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Data Kelas" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white md:overflow-hidden overflow-auto shadow-sm sm:rounded-lg p-4">
                        <div className="flex justify-between items-center p-2">
                            <div>
                                <h3 className='md:text-lg text-xs font-bold'>Data Kelas</h3>
                            </div>
                            <div>
                                <Link href={route('kelas-add')} className='bg-slate-800 text-white p-2 rounded-md md:text-lg text-xs'>Tambah Data</Link>
                            </div>
                        </div>
                        <div className="flex justify-between items-center p-2">
                            <div className='my-2'>
                                <select
                                    name="perpage"
                                    id="perpage"
                                    className='rounded-lg md:text-sm text-xs'
                                    value={perpage.current}
                                    onChange={handleChangeValue}
                                >
                                    <option>10</option>
                                    <option>20</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                            </div>
                            <div>
                                <form onSubmit={handlerSearch}>
                                    <div className="flex items-center gap-2">
                                        <TextInput
                                            className="md:text-sm text-xs"
                                            name="cari"
                                            type="search"
                                            placeholder="Cari"
                                            value={cari}
                                            onChange={(e) => setCari(e.target.value)}
                                        />
                                        <PrimaryButton type="submit">Cari</PrimaryButton>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table id="example" className="w-full p-4 border">
                            <thead>
                                <tr className='[&>th]:p-2 bg-slate-800 text-white'>
                                    <th className='text-left md:text-sm text-xs'>No</th>
                                    <th className='text-left md:text-sm text-xs'>Materi</th>
                                    <th className='text-left md:text-sm text-xs'>Kode Kelas</th>
                                    <th className='text-left md:text-sm text-xs'>Jenis</th>
                                    <th className='text-left md:text-sm text-xs'>Pertemuan</th>
                                    <th className='text-left md:text-sm text-xs'>Harga</th>
                                    <th className='text-left md:text-sm text-xs'>Foto</th>
                                    <th className='text-left md:text-sm text-xs w-24'>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                {
                                    isloadong ? (
                                        <tr>
                                            <td colSpan={5} className='text-center md:text-sm text-xs'>
                                                <lottie-player
                                                    src="https://lottie.host/cbcdd4c1-5bf6-40fa-aeba-2f9344e967bd/D84dkkW3KV.json"
                                                    background="#fff"
                                                    speed="1"
                                                    style={{ width: '200px', height: '200px', margin: 'auto' }}
                                                    loop
                                                    autoplay
                                                    direction="1"
                                                    mode="normal">
                                                </lottie-player>
                                            </td>
                                        </tr>
                                    ) :
                                        kelas.data.length < 1 ?
                                            <tr>
                                                <td colSpan={5} className='text-center p-2 md:text-sm text-xs'>
                                                    <lottie-player
                                                        src="https://lottie.host/d7294ce8-356d-48f3-a3b4-a551c2be7bed/p3BZckF4yh.json"
                                                        background="#fff"
                                                        speed="1"
                                                        style={{ width: '200px', height: '200px', margin: 'auto' }}
                                                        loop
                                                        autoplay
                                                        direction="1"
                                                        mode="normal">
                                                    </lottie-player>
                                                </td>
                                            </tr>
                                            :
                                            (
                                                kelas.data.map((data, i) => (
                                                    <tr key={data.id} className='[&>td]:p-2 text-sm border border-grey-100'>
                                                        <td className='border border-grey-100'>{kelas.from + i}</td>
                                                        <td className='border border-grey-100'>{data.materi}</td>
                                                        <td className='border border-grey-100'>{data.kode_kelas}</td>
                                                        <td className='border border-grey-100'>{data.jenis}</td>
                                                        <td className='border border-grey-100'>{data.pertemuan}</td>
                                                        <td className='border border-grey-100 text-right'>{IDR.format(data.harga)}</td>
                                                        <td className='border border-grey-100'><img src={data.foto ?? ""} alt="image" className='w-24' /></td>
                                                        <td className='border'>
                                                            <div className='grid grid-cols-2 gap-2 justify-items-center items-center'>
                                                                <Link href={ route('kelas-kode-mapel-add',data.id) } className='w-8 h-8 bg-blue-700 hover:bg-blue-600 text-white text-center p-2 rounded-lg md:text-sm text-xs' >
                                                                    <FontAwesomeIcon icon={faFile} />
                                                                </Link>
                                                                <Link href={ route('kelas-add',data.id) } className='w-8 h-8 hover:bg-blue-400 bg-blue-500 text-white text-center p-2 rounded-lg md:text-sm text-xs'>
                                                                    <FontAwesomeIcon icon={faPencil} />
                                                                </Link>
                                                                <div onClick={() => handlerHapus(data.id)} className='w-8 h-8 hover:cursor-pointer hover:bg-red-400 bg-red-500 text-white p-2 text-center m-1 rounded-lg md:text-sm text-xs'><FontAwesomeIcon icon={faTrash}/></div>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                )
                                                )
                                            )
                                }
                            </tbody>

                        </table>
                        <div className="flex items-center justify-between p-2">
                            <div className='md:text-sm text-xs'>
                                Melihat {kelas.from ?? 0} sampai {kelas.to ?? 0} dari {kelas.total ?? 0} data
                            </div>
                            <div className="flex items-center gap-2">
                                {kelas.links.map((link, i) => (
                                    <Link
                                        key={i}
                                        href={link.url}
                                        className='bg-slate-800 p-2 text-white md:text-sm text-xs rounded-md'
                                    >
                                        <div dangerouslySetInnerHTML={{
                                            __html: link.label,
                                        }} />

                                    </Link>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
