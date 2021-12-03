import axios from 'axios';
import { OsuUser } from '../decl/osuUser.decl';
import { Authorization } from '../decl/Authorization.decl';

let config = {
    headers: {
        Authorization: localStorage.getItem("userToken"),
    }
}

export const getOsuPseudo = async (user: OsuUser): Promise<OsuUser>=> {
    console.log(config);
    try{
        const res = await axios.post(
            `${process.env.REACT_APP_BASE_URL}/pseudo`,
            user,
            { headers: {    
                    'Authorization': window.localStorage.getItem("UserToken") ?? "",
               }
            }
        )
        return res.data;
    } catch(error) {        
        throw new Error("Erreur dans le pseudo ou pseudo inexistant");
    }
}; 