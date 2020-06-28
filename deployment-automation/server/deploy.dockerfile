FROM scratch

COPY ./deploy /

ENTRYPOINT [ "/deploy" ]