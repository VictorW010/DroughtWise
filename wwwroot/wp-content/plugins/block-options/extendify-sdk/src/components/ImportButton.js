import {
    useEffect, useState, useRef,
} from '@wordpress/element'
import { useTemplatesStore } from '../state/Templates'
import { AuthorizationCheck, Middleware } from '../middleware'
import { injectTemplateBlocks } from '../util/templateInjection'
import { useWantedTemplateStore } from '../state/Importing'
import { useUserStore } from '../state/User'
import { useGlobalStore } from '../state/GlobalState'
import { __, sprintf } from '@wordpress/i18n'
import { Templates as TemplatesApi } from '../api/Templates'

const canImportMiddleware = Middleware(['hasRequiredPlugins', 'hasPluginsActivated'])
export function ImportButton({ template }) {
    const importButtonRef = useRef(null)
    const activeTemplateBlocks = useTemplatesStore(state => state.activeTemplateBlocks)
    const canImport = useUserStore(state => state.canImport)
    const apiKey = useUserStore(state => state.apiKey)
    const setOpen = useGlobalStore(state => state.setOpen)
    const [importing, setImporting] = useState(false)
    const [middlewareChecked, setMiddlewareChecked] = useState(false)
    const setWanted = useWantedTemplateStore(state => state.setWanted)
    const importTemplates = () => {
        AuthorizationCheck(canImportMiddleware.stack).then(() => {
            // Give it a bit of time for the importing state to render
            setTimeout(() => {
                injectTemplateBlocks(activeTemplateBlocks, template).then(() => setOpen(false))
            }, 100)
        })
    }

    useEffect(() => {
        canImportMiddleware.check(template).then(() => setMiddlewareChecked(true))
        return () => canImportMiddleware.reset() && setMiddlewareChecked(false)
    }, [template])

    useEffect(() => {
        if (!importing && importButtonRef.current) {
            importButtonRef.current.focus()
        }
    }, [importButtonRef, importing, middlewareChecked])

    const importTemplate = () => {
        // This was added here to make the call fire before rendering is finished.
        // This is because some users would drop this call otherwise.
        TemplatesApi.maybeImport(template)
        setImporting(true)
        setWanted(template)
        importTemplates()
    }

    if (!middlewareChecked || !Object.keys(activeTemplateBlocks).length) {
        return ''
    }

    if (!apiKey && !canImport()) {
        return <a
            ref={importButtonRef}
            className="button-extendify-main text-lg sm:text-2xl py-1.5 px-3 sm:py-2.5 sm:px-5"
            target="_blank"
            href={`https://extendify.com/pricing?utm_source=${window.extendifySdkData.source}&utm_medium=library&utm_campaign=sign_up&utm_content=single_page`}
            rel="noreferrer">
            {__('Sign up now', 'extendify-sdk')}
        </a>
    }

    if (importing) {
        return <button
            type="button"
            disabled
            className="components-button is-secondary text-lg sm:text-2xl h-auto py-1.5 px-3 sm:py-2.5 sm:px-5"
            onClick={() => {}}>
            {__('Importing...', 'extendify-sdk')}
        </button>
    }

    return <button
        ref={importButtonRef}
        type="button"
        className="components-button is-primary text-lg sm:text-2xl h-auto py-1.5 px-3 sm:py-2.5 sm:px-5"
        onClick={() => importTemplate()}>
        {sprintf(__('Add %s', 'extendify-sdk'), template.fields.type)}
    </button>
}
