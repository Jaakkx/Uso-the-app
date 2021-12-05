import axios from 'axios';
import { OsuUser } from '../decl/osuUser.decl';
import { PlaylistReturn } from '../decl/playlistReturn.decl';

export const getOsuPseudo = async (user: OsuUser): Promise<OsuUser>=> {
    try{
        const res = await axios.post(
            `${process.env.REACT_APP_BASE_URL}/pseudo`,
            user,
            { headers: {    
                    'Authorization': localStorage.getItem("userToken") ?? "",
               }
            }
        )
        return res.data;
    } catch(error) {
        throw new Error("Erreur dans le pseudo ou pseudo inexistant");
    }
}; 

export const sendPlaylist = async (tab:PlaylistReturn): Promise<PlaylistReturn> => {
    try{
        const res = await axios.post(
            `${process.env.REACT_APP_BASE_URL}/createPlaylist`,
            tab
        )
        return res.data;
    }catch(error){
        throw new Error("Fdp revois tes requête");
    }
}